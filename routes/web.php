<?php

use App\Http\Controllers\UserController;
use Illuminate\Support\Facades\Route;


/*
|--------------------------------------------------------------------------
| UUID Generator
|--------------------------------------------------------------------------
*/


Route::get(
    '/',
    [UserController::class, 'dashboard']
)->name('uuid.dashboard');


/*
|--------------------------------------------------------------------------
| UUID Generation
|--------------------------------------------------------------------------
*/


Route::get(
    '/uuid',
    [UserController::class, 'uuid']
)->name('uuid.generate');


Route::get(
    '/ordered-uuid',
    [UserController::class, 'orderedUuid']
)->name('uuid.ordered');


Route::get(
    '/uuid7',
    [UserController::class, 'uuid7']
)->name('uuid.v7');


/*
|--------------------------------------------------------------------------
| UUID History
|--------------------------------------------------------------------------
*/


Route::get(
    '/uuid-history',
    [UserController::class, 'history']
)->name('uuid.history');


Route::delete(
    '/uuid-history/{uuidHistory}',
    [UserController::class, 'deleteHistory']
)->name('uuid.history.delete');


Route::delete(
    '/uuid-history-clear',
    [UserController::class, 'clearHistory']
)->name('uuid.history.clear');


/*
|--------------------------------------------------------------------------
| Regenerate UUID
|--------------------------------------------------------------------------
*/


Route::post(
    '/uuid-history/{uuidHistory}/regenerate',
    [UserController::class, 'regenerate']
)->name('uuid.history.regenerate');


/*
|--------------------------------------------------------------------------
| UUID Validator
|--------------------------------------------------------------------------
*/


Route::get(
    '/uuid-validator',
    function () {
        return view('uuid.validator');
    }
)->name('uuid.validator');


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


/*
|--------------------------------------------------------------------------
| History Export
|--------------------------------------------------------------------------
*/


Route::get(
    '/uuid-history/export/csv',
    [UserController::class, 'exportHistoryCsv']
)->name('uuid.history.export.csv');


Route::get(
    '/uuid-history/export/json',
    [UserController::class, 'exportHistoryJson']
)->name('uuid.history.export.json');