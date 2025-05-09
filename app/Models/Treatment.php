<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Treatment extends Model
{
    protected $fillable = [
        'appointment_id',
        'title',
        'description',
        'start_date',
        'end_date'
    ];
}
