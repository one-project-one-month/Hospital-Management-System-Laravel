<?php

namespace App\Models;

use App\Models\Medicine;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    protected $fillable=['appointment_id', 'amount', 'status', 'payment_method', 'due_date'];

    public function medicines()
    {
        return $this->belongsToMany(Medicine::class, 'invoice_medicine')
                    ->withPivot('quantity')
                    ->withTimestamps();
    }
}
