<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MedicalRecordMedicine extends Model
{
    protected $fillable = [
        'medical_record_id',
        'medicine_id',
        'quantity'
    ];    
}
