<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Concerns\HasUuids;
use Illuminate\Database\Eloquent\Model;

class RecordType extends Model
{

    use HasUuids;
    protected $fillable = [
        'name',
        'description',
    ];

    public function medical_record(){
        return $this->hasOne(MedicalRecord::class);
    }
}
