<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\RecordTypeController;

Route::prefix('record-types')->group(function () {

    Route::get('/', [RecordTypeController::class, 'index']);

    Route::get('/{recordType}', [RecordTypeController::class, 'show']);

    Route::post('/', [RecordTypeController::class, 'store']);

    Route::put('/{recordType}', [RecordTypeController::class, 'update']);

    Route::delete('/{recordType}', [RecordTypeController::class, 'destroy']);

});
