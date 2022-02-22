<?php

use App\Http\Controllers\API\RecordController;

use Illuminate\Support\Facades\Route;

Route::prefix('record')->group(function() {
    Route::get('init', [RecordController::class, 'initRecord']);
    Route::get('data/initial/{id}', [RecordController::class, 'getInitialData']);
    Route::get('data/{id}', [RecordController::class, 'getRecordData']);
    Route::get('data/paginated/{id}', [RecordController::class, 'getPaginatedRecordData']);
    Route::post('save', [RecordController::class, 'saveRecord']);
});
