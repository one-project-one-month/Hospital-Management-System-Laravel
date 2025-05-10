<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\PatientProfileController;
use App\Traits\HttpResponse;

Route::prefix('patient-profile')->middleware('auth:sanctum')->group(function(){
    Route::get('/', [PatientProfileController::class,'index']);
    Route::post('/', [PatientProfileController::class,'store']);
    Route::get('/{id}', [PatientProfileController::class,'show']);
    Route::put('/{id}', [PatientProfileController::class,'update']);
    Route::delete('/{id}', [PatientProfileController::class,'destroy']);
});
