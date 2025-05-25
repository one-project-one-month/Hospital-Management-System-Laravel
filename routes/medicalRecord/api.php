<?php

use App\Http\Controllers\Api\MedicalRecordController;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Route;

// get all medical records
Route::get('medical-records', [MedicalRecordController::class, 'index']);
//  get one medical record
Route::get('appointments/{appointment}/medical-record', [MedicalRecordController::class, 'show']);

Route::delete('appointments/{appointment}/medical-record', [MedicalRecordController::class, 'destroy']);
Route::put('appointments/{appointment}/medical-record', [MedicalRecordController::class, 'update']);
Route::post('appointments/{appointment}/medical-records', [MedicalRecordController::class, 'store']);
