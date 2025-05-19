<?php

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Models\InvoiceMedicine;
use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\AuthController;
use App\Http\Controllers\Api\AdminController;
use App\Http\Controllers\Api\InvoiceController;
use App\Http\Controllers\Api\MedicineController;
use App\Http\Controllers\Api\LabResultController;
use App\Http\Controllers\Api\TreatmentController;
use App\Http\Controllers\DoctorProfileController;
use App\Http\Controllers\Api\AppointmentController;
use App\Http\Controllers\InvoiceMedicineController;
use App\Http\Controllers\Api\PatientProfileController;



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
    // Route::get('users',[PatientProfileController::class,'getUsers']);
    Route::get('getMyPatientAccounts',[PatientProfileController::class,'getMyPatientAccounts']);

    Route::post('/invoices/{invoice}/medicines/sync',[InvoiceMedicineController::class,'store']);
    Route::get('/invoices/{invoice}/medicines',[InvoiceMedicineController::class,'index']);

    Route::apiResource('invoice/{appointment}/invoice/', InvoiceController::class);

    Route::post('/appointments/patient', [AppointmentController::class, 'createAppointmentFromPatient']);
    Route::get('/appointments/doctor', [AppointmentController::class, 'getDoctorAppointments']);
    Route::post('/appointments/receptionist', [AppointmentController::class, 'receptionistBookAppointment']);
    Route::post('admin/createReceptionist',[AdminController::class,'createReceptionist']);
    Route::post('admin/createDoctor',[AdminController::class,'createDoctor']);


    Route::get('/appointments/{role}', [AppointmentController::class, 'appointmentReadPatient']);
    Route::get('/appointments/all', [AppointmentController::class, 'index']);
    Route::get('admin/doctors', [DoctorProfileController::class, 'index']);
    Route::apiResource('lab-results', LabResultController::class);
    // Partient Route
    require __DIR__.'/partientProfile/api.php';

    // Record Type Route
    require __DIR__.'/recordType/api.php';

    // Medical Record Route
    require __DIR__.'/medicalRecord/api.php';

});

