<?php

namespace App\Http\Controllers;

use App\Models\UuidHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * UUID Dashboard
     */
    public function dashboard()
    {
        $total = UuidHistory::count();

        $uuidV4Count = UuidHistory::where('version', 'UUID v4')->count();

        $orderedUuidCount = UuidHistory::where(
            'version',
            'Ordered UUID'
        )->count();

        $uuidV7Count = UuidHistory::where(
            'version',
            'UUID v7'
        )->count();

        $todayCount = UuidHistory::whereDate(
            'generated_at',
            today()
        )->count();

        $recentUuids = UuidHistory::latest('generated_at')
            ->take(10)
            ->get();

        return view('uuid.dashboard', compact(
            'total',
            'uuidV4Count',
            'orderedUuidCount',
            'uuidV7Count',
            'todayCount',
            'recentUuids'
        ));
    }

    /**
     * Generate Normal UUID v4.
     */
    public function uuid()
    {
        $uuid = Str::uuid()->toString();

        $this->saveUuidHistory(
            $uuid,
            'UUID v4',
            'UUID v4'
        );

        return response()->json([
            'success' => true,
            'type' => 'UUID v4',
            'uuid' => $uuid,
        ]);
    }

    /**
     * Generate Ordered UUID.
     */
    public function orderedUuid()
    {
        $uuid = Str::orderedUuid()->toString();

        $this->saveUuidHistory(
            $uuid,
            'Ordered UUID',
            'Ordered UUID'
        );

        return response()->json([
            'success' => true,
            'type' => 'Ordered UUID',
            'uuid' => $uuid,
        ]);
    }

    /**
     * Generate UUID v7.
     */
    public function uuid7()
    {
        $uuid = Str::uuid7()->toString();

        $this->saveUuidHistory(
            $uuid,
            'UUID v7',
            'UUID v7'
        );

        return response()->json([
            'success' => true,
            'type' => 'UUID v7',
            'uuid' => $uuid,
        ]);
    }

    /**
     * Save generated UUID in history.
     */
    private function saveUuidHistory(
        string $uuid,
        string $type,
        string $version
    ): void {
        UuidHistory::create([
            'uuid' => $uuid,
            'type' => $type,
            'version' => $version,
            'generated_at' => now(),
        ]);
    }

    /**
     * UUID Generation History.
     */
    public function history(Request $request)
    {
        $query = UuidHistory::query();

        /*
        |--------------------------------------------------------------------------
        | Search
        |--------------------------------------------------------------------------
        */

        if ($request->filled('search')) {
            $search = $request->search;

            $query->where(function ($q) use ($search) {
                $q->where('uuid', 'like', "%{$search}%")
                    ->orWhere('type', 'like', "%{$search}%")
                    ->orWhere('version', 'like', "%{$search}%");
            });
        }

        /*
        |--------------------------------------------------------------------------
        | Version Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('version')) {
            $query->where('version', $request->version);
        }

        /*
        |--------------------------------------------------------------------------
        | Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date')) {
            $query->whereDate(
                'generated_at',
                $request->date
            );
        }

        $histories = $query
            ->latest('generated_at')
            ->paginate(10)
            ->withQueryString();

        return view('uuid.history', compact('histories'));
    }

    /**
     * UUID Validation and Version Detection.
     */
    public function validateUuid(Request $request)
    {
        $request->validate([
            'uuid' => [
                'required',
                'string',
                'max:36',
            ],
        ]);

        $uuid = strtolower(trim($request->uuid));

        /*
        |--------------------------------------------------------------------------
        | UUID Format Validation
        |--------------------------------------------------------------------------
        */

        $isValidFormat = preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid
        );

        if (!$isValidFormat) {
            return view('uuid.validator', [
                'result' => [
                    'valid' => false,
                    'uuid' => $uuid,
                    'version' => 'Unknown',
                    'message' => 'Invalid UUID format.',
                ],
            ]);
        }

        /*
        |--------------------------------------------------------------------------
        | Detect UUID Version
        |--------------------------------------------------------------------------
        */

        $versionNumber = (int) $uuid[14];

        $version = match ($versionNumber) {
            1 => 'UUID v1',
            2 => 'UUID v2',
            3 => 'UUID v3',
            4 => 'UUID v4',
            5 => 'UUID v5',
            6 => 'UUID v6',
            7 => 'UUID v7',
            8 => 'UUID v8',
            default => 'Unknown',
        };

        /*
        |--------------------------------------------------------------------------
        | Detect Whether It Exists In History
        |--------------------------------------------------------------------------
        */

        $history = UuidHistory::where(
            'uuid',
            $uuid
        )->first();

        return view('uuid.validator', [
            'result' => [
                'valid' => true,
                'uuid' => $uuid,
                'version' => $version,
                'message' => 'Valid UUID detected.',
                'exists_in_history' => $history !== null,
                'generated_type' => $history?->type,
            ],
        ]);
    }

    /**
     * Delete one UUID history record.
     */
    public function deleteHistory(UuidHistory $uuidHistory)
    {
        $uuidHistory->delete();

        return redirect()
            ->route('uuid.history')
            ->with('success', 'UUID history deleted successfully.');
    }

    /**
     * Clear all UUID history.
     */
    public function clearHistory()
    {
        UuidHistory::truncate();

        return redirect()
            ->route('uuid.history')
            ->with('success', 'UUID generation history cleared successfully.');
    }

    /*
    |--------------------------------------------------------------------------
    | BULK UUID GENERATOR
    |--------------------------------------------------------------------------
    */

    /**
     * Show bulk UUID generator page.
     */
    public function bulkGenerator()
    {
        return view('uuid.bulk');
    }

    /**
     * Generate multiple UUIDs.
     */
    public function bulkGenerate(Request $request)
    {
        /*
        |--------------------------------------------------------------------------
        | Validate Request
        |--------------------------------------------------------------------------
        */

        $validated = $request->validate([
            'type' => [
                'required',
                'in:UUID v4,Ordered UUID,UUID v7',
            ],

            'quantity' => [
                'required',
                'integer',
                'min:1',
                'max:100',
            ],
        ]);

        $type = $validated['type'];

        $quantity = (int) $validated['quantity'];

        $generatedUuids = [];

        /*
        |--------------------------------------------------------------------------
        | Generate UUIDs
        |--------------------------------------------------------------------------
        */

        for ($i = 0; $i < $quantity; $i++) {

            if ($type === 'UUID v4') {

                $uuid = Str::uuid()->toString();

            } elseif ($type === 'Ordered UUID') {

                $uuid = Str::orderedUuid()->toString();

            } else {

                $uuid = Str::uuid7()->toString();
            }

            $generatedUuids[] = $uuid;

            /*
            |--------------------------------------------------------------------------
            | Save To History
            |--------------------------------------------------------------------------
            */

            $this->saveUuidHistory(
                $uuid,
                $type,
                $type
            );
        }

        /*
        |--------------------------------------------------------------------------
        | Return Bulk Generator View
        |--------------------------------------------------------------------------
        */

        return view('uuid.bulk', [
            'generatedUuids' => $generatedUuids,
            'type' => $type,
            'quantity' => $quantity,
        ]);
    }

    /**
     * Export generated UUIDs as CSV.
     */
    public function exportBulkCsv(Request $request)
    {
        $request->validate([
            'type' => [
                'required',
                'in:UUID v4,Ordered UUID,UUID v7',
            ],

            'uuids' => [
                'required',
                'string',
            ],
        ]);

        $type = $request->type;

        $uuids = array_filter(
            explode("\n", $request->uuids)
        );

        $filename =
            'uuid-' .
            strtolower(
                str_replace(' ', '-', $type)
            ) .
            '-' .
            now()->format('Y-m-d-H-i-s') .
            '.csv';

        return response()->streamDownload(function () use (
            $uuids,
            $type
        ) {

            $handle = fopen('php://output', 'w');

            /*
            |--------------------------------------------------------------------------
            | CSV Header
            |--------------------------------------------------------------------------
            */

            fputcsv($handle, [
                'No.',
                'UUID',
                'Type',
                'Generated At',
            ]);

            /*
            |--------------------------------------------------------------------------
            | CSV Records
            |--------------------------------------------------------------------------
            */

            foreach ($uuids as $index => $uuid) {

                fputcsv($handle, [
                    $index + 1,
                    trim($uuid),
                    $type,
                    now()->format('Y-m-d H:i:s'),
                ]);
            }

            fclose($handle);

        }, $filename, [
            'Content-Type' => 'text/csv',
        ]);
    }
}