<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Appointment extends Model
{
    protected $fillable = [
        'patient_profile_id',
        'doctor_profile_id',
        'appointment_date',
        'appointment_time',
        'status',
        'notes'
    ];
}
