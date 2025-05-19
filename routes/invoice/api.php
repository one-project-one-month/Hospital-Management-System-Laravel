<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\InvoiceController;
use App\Traits\HttpResponse;

Route::prefix('invoice')->middleware('auth:sanctum')->group(function () {
    Route::get('/', [InvoiceController::class, 'index']);
    Route::post('/{appointment}', [InvoiceController::class, 'store']);
    Route::get('/{id}', [InvoiceController::class, 'show']);
    Route::put('/{id}', [InvoiceController::class, 'update']);
});
