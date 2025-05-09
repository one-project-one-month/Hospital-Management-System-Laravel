<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class InvoiceMedicine extends Model
{
    protected $fillable=['invoice_id','medicine_id','quantity'];
}
