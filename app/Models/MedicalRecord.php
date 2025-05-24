<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class MedicalRecord extends Model
{
    protected $fillable = [
        'appointment_id',
        'record_type_id',
        'title',
        'description',
        'recorded_at',
        'medicine_price'
    ];

    public function casts(): array
    {
        return [
            'recorded_at' => 'date'
        ];
    }

    public function appointment(): BelongsTo
    {
        return $this->belongsTo(Appointment::class);
    }

    public function record_type(): BelongsTo
    {
        return $this->belongsTo(RecordType::class);
    }

    public function medicines(): BelongsToMany
    {
        return $this->belongsToMany(Medicine::class, 'medical_record_medicine', 'medical_record_id', 'medicine_id')->withPivot('quantity');
    }
}
