<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| UUID Generator
|--------------------------------------------------------------------------
*/

// Dashboard
Route::get('/', [UserController::class, 'dashboard'])
    ->name('uuid.dashboard');

// Generate UUID v4
Route::get('/uuid', [UserController::class, 'uuid'])
    ->name('uuid.generate');

// Generate Ordered UUID
Route::get('/ordered-uuid', [UserController::class, 'orderedUuid'])
    ->name('uuid.ordered');

// Generate UUID v7
Route::get('/uuid7', [UserController::class, 'uuid7'])
    ->name('uuid.v7');


/*
|--------------------------------------------------------------------------
| UUID History
|--------------------------------------------------------------------------
*/

// UUID generation history
Route::get('/uuid-history', [UserController::class, 'history'])
    ->name('uuid.history');

// Delete individual history
Route::delete(
    '/uuid-history/{uuidHistory}',
    [UserController::class, 'deleteHistory']
)->name('uuid.history.delete');

// Clear all history
Route::delete(
    '/uuid-history-clear',
    [UserController::class, 'clearHistory']
)->name('uuid.history.clear');


/*
|--------------------------------------------------------------------------
| UUID Validator
|--------------------------------------------------------------------------
*/

Route::get('/uuid-validator', function () {
    return view('uuid.validator');
})->name('uuid.validator');

Route::post(
    '/uuid-validator',
    [UserController::class, 'validateUuid']
)->name('uuid.validator.check');


/*
|--------------------------------------------------------------------------
| Bulk UUID Generator
|--------------------------------------------------------------------------
*/

Route::get(
    '/bulk-uuid',
    [UserController::class, 'bulkGenerator']
)->name('uuid.bulk');

Route::post(
    '/bulk-uuid/generate',
    [UserController::class, 'bulkGenerate']
)->name('uuid.bulk.generate');

Route::post(
    '/bulk-uuid/export',
    [UserController::class, 'exportBulkCsv']
)->name('uuid.bulk.export');