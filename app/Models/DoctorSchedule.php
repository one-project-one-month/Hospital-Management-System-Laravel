<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DoctorSchedule extends Model
{
    protected $fillable = [
        'doctor_profile_id',
        'weekday',
        'start_time',
        'end_time'
    ];
}
