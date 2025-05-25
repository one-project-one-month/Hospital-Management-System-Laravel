<?php

use App\Http\Controllers\Api\MedicalRecordController;
use App\Models\MedicalRecord;
use Illuminate\Support\Facades\Route;


Route::apiResource('medical-records', MedicalRecordController::class);
