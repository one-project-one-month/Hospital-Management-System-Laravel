<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Treatment extends Model
{

    protected $fillable = [
        'appointment_id',
        'title',
        'description',
        'start_date',
        'end_date'
    ];


    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
