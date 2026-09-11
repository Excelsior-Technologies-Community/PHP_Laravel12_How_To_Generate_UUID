<?php

namespace App\Http\Controllers;

use App\Models\UuidHistory;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Response;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | DASHBOARD
    |--------------------------------------------------------------------------
    */

    public function dashboard()
    {
        $total = UuidHistory::count();

        $uuidV4Count = UuidHistory::where(
            'version',
            'UUID v4'
        )->count();

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

        /*
        |--------------------------------------------------------------------------
        | NEW FUNCTIONALITY 1
        | 7 Day Statistics
        |--------------------------------------------------------------------------
        */

        $sevenDayStats = [];

        for ($i = 6; $i >= 0; $i--) {

            $date = now()->subDays($i);

            $sevenDayStats[] = [
                'date' => $date->format('d M'),
                'count' => UuidHistory::whereDate(
                    'generated_at',
                    $date
                )->count(),
            ];
        }

        /*
        |--------------------------------------------------------------------------
        | Recent UUIDs
        |--------------------------------------------------------------------------
        */

        $recentUuids = UuidHistory::oldest(
            'generated_at'
        )->take(5)->get();

        return view(
            'uuid.dashboard',
            compact(
                'total',
                'uuidV4Count',
                'orderedUuidCount',
                'uuidV7Count',
                'todayCount',
                'recentUuids',
                'sevenDayStats'
            )
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UUID V4
    |--------------------------------------------------------------------------
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


    /*
    |--------------------------------------------------------------------------
    | ORDERED UUID
    |--------------------------------------------------------------------------
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


    /*
    |--------------------------------------------------------------------------
    | UUID V7
    |--------------------------------------------------------------------------
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


    /*
    |--------------------------------------------------------------------------
    | SAVE HISTORY
    |--------------------------------------------------------------------------
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


    /*
    |--------------------------------------------------------------------------
    | UUID HISTORY
    |--------------------------------------------------------------------------
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

                $q->where(
                    'uuid',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'type',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'version',
                    'like',
                    "%{$search}%"
                );
            });
        }


        /*
        |--------------------------------------------------------------------------
        | Version Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('version')) {

            $query->where(
                'version',
                $request->version
            );
        }


        /*
        |--------------------------------------------------------------------------
        | Existing Exact Date Filter
        |--------------------------------------------------------------------------
        */

        if ($request->filled('date')) {

            $query->whereDate(
                'generated_at',
                $request->date
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NEW FUNCTIONALITY 2
        | From Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('from_date')) {

            $query->whereDate(
                'generated_at',
                '>=',
                $request->from_date
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NEW FUNCTIONALITY 2
        | To Date
        |--------------------------------------------------------------------------
        */

        if ($request->filled('to_date')) {

            $query->whereDate(
                'generated_at',
                '<=',
                $request->to_date
            );
        }


        /*
        |--------------------------------------------------------------------------
        | NEW FUNCTIONALITY 3
        | Sorting
        |--------------------------------------------------------------------------
        */

        $allowedSorts = [
            'id',
            'uuid',
            'type',
            'version',
            'generated_at',
        ];

        $sort = $request->get(
            'sort',
            'generated_at'
        );

        if (!in_array($sort, $allowedSorts)) {
            $sort = 'generated_at';
        }

        $direction = $request->get(
            'direction',
            'desc'
        );

        if (!in_array(
            $direction,
            ['asc', 'desc']
        )) {
            $direction = 'desc';
        }

        $query->orderBy(
            $sort,
            $direction
        );


        /*
        |--------------------------------------------------------------------------
        | NEW FUNCTIONALITY 4
        | Per Page
        |--------------------------------------------------------------------------
        */

        $perPage = (int) $request->get(
            'per_page',
            5
        );

        if (!in_array(
            $perPage,
            [5,10, 25, 50, 100]
        )) {
            $perPage = 5;
        }


        $histories = $query
            ->paginate($perPage)
            ->withQueryString();


        return view(
            'uuid.history',
            compact('histories')
        );
    }


    /*
    |--------------------------------------------------------------------------
    | UUID VALIDATOR
    |--------------------------------------------------------------------------
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

        $uuid = strtolower(
            trim($request->uuid)
        );


        /*
        |--------------------------------------------------------------------------
        | UUID FORMAT VALIDATION
        |--------------------------------------------------------------------------
        */

        $isValidFormat = preg_match(
            '/^[0-9a-f]{8}-[0-9a-f]{4}-[1-8][0-9a-f]{3}-[89ab][0-9a-f]{3}-[0-9a-f]{12}$/i',
            $uuid
        );


        if (!$isValidFormat) {

            return view(
                'uuid.validator',
                [
                    'result' => [
                        'valid' => false,
                        'uuid' => $uuid,
                        'version' => 'Unknown',
                        'message' => 'Invalid UUID format.',
                        'exists_in_history' => false,
                        'generated_type' => null,
                        'is_unique' => null,
                        'timestamp' => null,
                    ],
                ]
            );
        }


        /*
        |--------------------------------------------------------------------------
        | VERSION DETECTION
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
        | HISTORY CHECK
        |--------------------------------------------------------------------------
        */

        $history = UuidHistory::where(
            'uuid',
            $uuid
        )->first();


        /*
        |--------------------------------------------------------------------------
        | NEW FUNCTIONALITY 7
        | UUID Uniqueness Check
        |--------------------------------------------------------------------------
        */

        $existsInHistory = $history !== null;

        $isUnique = !$existsInHistory;


        /*
        |--------------------------------------------------------------------------
        | NEW FUNCTIONALITY 8
        | UUID Timestamp Information
        |--------------------------------------------------------------------------
        */

        $timestamp = null;

        if ($versionNumber === 7) {

            try {

                $first48Bits = substr(
                    str_replace('-', '', $uuid),
                    0,
                    12
                );

                $milliseconds = hexdec(
                    $first48Bits
                );

                $timestamp = date(
                    'Y-m-d H:i:s',
                    (int) ($milliseconds / 1000)
                );

            } catch (\Throwable $e) {

                $timestamp = null;
            }
        }


        return view(
            'uuid.validator',
            [
                'result' => [
                    'valid' => true,
                    'uuid' => $uuid,
                    'version' => $version,
                    'message' => 'Valid UUID detected.',
                    'exists_in_history' => $existsInHistory,
                    'generated_type' => $history?->type,
                    'is_unique' => $isUnique,
                    'timestamp' => $timestamp,
                ],
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | DELETE ONE HISTORY RECORD
    |--------------------------------------------------------------------------
    */

    public function deleteHistory(
        UuidHistory $uuidHistory
    ) {
        $uuidHistory->delete();

        return redirect()
            ->route('uuid.history')
            ->with(
                'success',
                'UUID history deleted successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | CLEAR ALL HISTORY
    |--------------------------------------------------------------------------
    */

    public function clearHistory()
    {
        UuidHistory::truncate();

        return redirect()
            ->route('uuid.history')
            ->with(
                'success',
                'UUID generation history cleared successfully.'
            );
    }


    /*
    |--------------------------------------------------------------------------
    | BULK UUID GENERATOR
    |--------------------------------------------------------------------------
    */

    public function bulkGenerator()
    {
        return view('uuid.bulk');
    }


    /*
    |--------------------------------------------------------------------------
    | BULK GENERATE
    |--------------------------------------------------------------------------
    */

    public function bulkGenerate(Request $request)
    {
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


        for ($i = 0; $i < $quantity; $i++) {

            if ($type === 'UUID v4') {

                $uuid = Str::uuid()->toString();

            } elseif ($type === 'Ordered UUID') {

                $uuid = Str::orderedUuid()->toString();

            } else {

                $uuid = Str::uuid7()->toString();
            }


            $generatedUuids[] = $uuid;


            $this->saveUuidHistory(
                $uuid,
                $type,
                $type
            );
        }


        return view(
            'uuid.bulk',
            [
                'generatedUuids' => $generatedUuids,
                'type' => $type,
                'quantity' => $quantity,
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | BULK CSV EXPORT
    |--------------------------------------------------------------------------
    */

    public function exportBulkCsv(
        Request $request
    ) {
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
            explode(
                "\n",
                $request->uuids
            )
        );


        $filename =
            'uuid-' .
            strtolower(
                str_replace(
                    ' ',
                    '-',
                    $type
                )
            ) .
            '-' .
            now()->format(
                'Y-m-d-H-i-s'
            ) .
            '.csv';


        return response()->streamDownload(
            function () use (
                $uuids,
                $type
            ) {

                $handle = fopen(
                    'php://output',
                    'w'
                );

                fputcsv(
                    $handle,
                    [
                        'No.',
                        'UUID',
                        'Type',
                        'Generated At',
                    ]
                );


                foreach (
                    $uuids as $index => $uuid
                ) {

                    fputcsv(
                        $handle,
                        [
                            $index + 1,
                            trim($uuid),
                            $type,
                            now()->format(
                                'Y-m-d H:i:s'
                            ),
                        ]
                    );
                }


                fclose($handle);

            },
            $filename,
            [
                'Content-Type' => 'text/csv',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NEW FUNCTIONALITY 5
    | EXPORT COMPLETE HISTORY CSV
    |--------------------------------------------------------------------------
    */

    public function exportHistoryCsv(
        Request $request
    ) {
        $query = UuidHistory::query();


        if ($request->filled('search')) {

            $search = $request->search;

            $query->where(function ($q) use (
                $search
            ) {

                $q->where(
                    'uuid',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'type',
                    'like',
                    "%{$search}%"
                )
                ->orWhere(
                    'version',
                    'like',
                    "%{$search}%"
                );
            });
        }


        if ($request->filled('version')) {

            $query->where(
                'version',
                $request->version
            );
        }


        if ($request->filled('from_date')) {

            $query->whereDate(
                'generated_at',
                '>=',
                $request->from_date
            );
        }


        if ($request->filled('to_date')) {

            $query->whereDate(
                'generated_at',
                '<=',
                $request->to_date
            );
        }


        $histories = $query
            ->oldest('generated_at')
            ->get();


        $filename =
            'uuid-history-' .
            now()->format(
                'Y-m-d-H-i-s'
            ) .
            '.csv';


        return response()->streamDownload(
            function () use (
                $histories
            ) {

                $handle = fopen(
                    'php://output',
                    'w'
                );


                fputcsv(
                    $handle,
                    [
                        'ID',
                        'UUID',
                        'Type',
                        'Version',
                        'Generated At',
                    ]
                );


                foreach (
                    $histories as $history
                ) {

                    fputcsv(
                        $handle,
                        [
                            $history->id,
                            $history->uuid,
                            $history->type,
                            $history->version,
                            $history->generated_at
                                ->format(
                                    'Y-m-d H:i:s'
                                ),
                        ]
                    );
                }


                fclose($handle);

            },
            $filename,
            [
                'Content-Type' => 'text/csv',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NEW FUNCTIONALITY 6
    | EXPORT HISTORY JSON
    |--------------------------------------------------------------------------
    */

    public function exportHistoryJson(
        Request $request
    ) {
        $histories = UuidHistory::oldest(
            'generated_at'
        )->get();


        $filename =
            'uuid-history-' .
            now()->format(
                'Y-m-d-H-i-s'
            ) .
            '.json';


        return response()->streamDownload(
            function () use (
                $histories
            ) {

                echo json_encode(
                    $histories,
                    JSON_PRETTY_PRINT
                );

            },
            $filename,
            [
                'Content-Type' =>
                    'application/json',
            ]
        );
    }


    /*
    |--------------------------------------------------------------------------
    | NEW FUNCTIONALITY 9
    | REGENERATE FROM HISTORY TYPE
    |--------------------------------------------------------------------------
    */

    public function regenerate(
        UuidHistory $uuidHistory
    ) {
        $type = $uuidHistory->type;


        if ($type === 'UUID v4') {

            $uuid = Str::uuid()->toString();

        } elseif ($type === 'Ordered UUID') {

            $uuid = Str::orderedUuid()->toString();

        } else {

            $uuid = Str::uuid7()->toString();
        }


        $this->saveUuidHistory(
            $uuid,
            $type,
            $type
        );


        return redirect()
            ->route('uuid.history')
            ->with(
                'success',
                "New {$type} generated successfully."
            );
    }
}