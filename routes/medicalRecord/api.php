<?php

use App\Http\Controllers\Api\MedicalRecordController;
use Illuminate\Support\Facades\Route;

Route::prefix('medical-record')->group(function(){
    Route::post('/', [MedicalRecordController::class, 'store']);
});
