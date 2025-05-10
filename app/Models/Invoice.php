<?php

namespace App\Models;

use App\Models\Medicine;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable=[''];

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'invoice_medicine')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
