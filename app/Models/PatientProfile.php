<?php

namespace App\Models;

use App\Enums\PatientProfile\Gender;
use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PatientProfile extends Model
{
    use HasFactory,HasUuids;

    protected $fillable=[
        'user_id',
        'date_of_birth',
        'gender',
        'phone',
        'address',
        'relation',
        'blood_type',
    ];

    protected $appends = [
        'gender_label'
    ];

    protected function casts(): array
    {
        return [
            'gender' => Gender::class
        ];
    }

    public function getGenderLabelAttribute(): string
    {
        return $this->gender->label();
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class,'user_id');
    }
}
