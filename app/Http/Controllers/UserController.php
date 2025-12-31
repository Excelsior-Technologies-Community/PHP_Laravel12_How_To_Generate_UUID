<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

class UserController extends Controller
{
    /**
     * Generate Normal UUID (v4)
     */
    public function uuid()
    {
        $uuid = Str::uuid()->toString();

        return response()->json([
            'type' => 'UUID v4',
            'uuid' => $uuid
        ]);
    }

    /**
     * Generate Ordered UUID (Time-based)
     */
    public function orderedUuid()
    {
        $uuid = Str::orderedUuid()->toString();

        return response()->json([
            'type' => 'Ordered UUID',
            'uuid' => $uuid
        ]);
    }

    /**
     * Generate UUID v7 (Laravel 12)
     */
    public function uuid7()
    {
        $uuid = Str::uuid7()->toString();

        return response()->json([
            'type' => 'UUID v7',
            'uuid' => $uuid
        ]);
    }
}
