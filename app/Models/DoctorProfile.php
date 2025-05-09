<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class DoctorProfile extends Model
{
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
            'specialty' => 'array',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }
}
