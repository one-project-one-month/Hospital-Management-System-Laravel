<?php

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\PatientProfileController;
use App\Http\Controllers\Api\TreatmentController;
use App\Http\Controllers\InvoiceMedicineController;
use App\Models\InvoiceMedicine;
use App\Traits\HttpResponse;

Route::get('/user', function (Request $request) {
    return $request->user();
})->middleware('auth:sanctum');

Route::prefix('v1/auth')->group(function(){
    Route::post('register',[AuthController::class,'register']);
    Route::post('login',[AuthController::class,'login']);

    Route::middleware('auth:sanctum')->group(function(){
        Route::get('user',[AuthController::class,'user']);
        Route::post('logout',[AuthController::class,'logout']);
    });
});

Route::get('/error', function () {
    return HttpResponse::fail('fail', null, 'Not Authenticated', 401);
})->name('login');

Route::prefix('v1')->middleware('auth:sanctum')->group(function(){
    Route::apiResource('medicines',MedicineController::class);
    Route::apiResource('appointments/{appointment}/treatments',TreatmentController::class);

    Route::post('/invoices/{invoice}/medicines/sync',[InvoiceMedicineController::class,'store']);
    Route::get('/invoices/{invoice}/medicines',[InvoiceMedicineController::class,'index']);


    // Partient Route
    require __DIR__.'/partientProfile/api.php';

});

