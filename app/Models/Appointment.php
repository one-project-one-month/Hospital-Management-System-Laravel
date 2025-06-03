<?php

namespace App\Models;

use App\Models\DoctorProfile;
use App\Models\PatientProfile;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

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

    public function patientProfile()
    {
        return $this->belongsTo(PatientProfile::class, 'patient_profile_id');
    }

    public function doctorProfile()
    {
        return $this->belongsTo(DoctorProfile::class, 'doctor_profile_id');
    }

    public function medicalRecord(): HasOne
    {
        return $this->hasOne(MedicalRecord::class, 'appointment_id');
    }

    public function treatment() : BelongsTo
    {
        return $this->hasOne(Treatment::class);
    }

    public function labResults()
    {
        return $this->hasMany(LabResult::class);
    }

    public function scopeFilterBy($query, array $filters)
    {
        return $query->when(isset($filters['doctor_id']), fn($q) => $q->where('doctor_profile_id', $filters['doctor_id']))
                     ->when(isset($filters['appointment_date']), fn($q) => $q->where('appointment_date', $filters['appointment_date']))
                     ->when(isset($filters['patient_profile_id']), fn($q) => $q->where('patient_profile_id', $filters['patient_profile_id']))
                     ->when(isset($filters['status']), fn($q) => $q->where('status', $filters['status']));
    }


}
