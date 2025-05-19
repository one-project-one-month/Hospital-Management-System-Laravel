<?php

namespace App\Models;

use App\Models\Treatment;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorProfile extends Model
{
    use HasUuids;

    protected $fillable = [
        'user_id',
        'specialty',
        'license_number',
        'education',
        'experience_years',
        'biography',
        'phone',
        'address',
    ];

    protected function casts(): array
    {
        return [
            'speciality' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function treatments()
    {
        return $this->hasManyThrough(
            Treatment::class,
            Appointment::class,
            'doctor_profile_id', // Foreign key on appointments
            'appointment_id',    // Foreign key on treatments
            'id',                // Local key on doctor_profiles
            'id'                 // Local key on appointments
        );
    }
}
