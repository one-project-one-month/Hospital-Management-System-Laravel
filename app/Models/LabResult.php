<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LabResult extends Model
{
    protected $fillable = [
        'appointment_id',
        'test_name',
        'result_summary',
        'detailed_result',
        'performed_at'
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
