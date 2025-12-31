<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

// Generate a random UUID (UUID v4)
Route::get('/uuid', [UserController::class, 'uuid']);

// Generate a time-based ordered UUID
Route::get('/ordered-uuid', [UserController::class, 'orderedUuid']);

// Generate UUID v7 (recommended for Laravel 12)
Route::get('/uuid7', [UserController::class, 'uuid7']);
