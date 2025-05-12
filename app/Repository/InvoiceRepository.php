<?php

namespace App\Repository;

use App\Models\Invoice;

class InvoiceRepository{
    public function create($data){
        $invoice = Invoice::create($data);
        return $invoice;
    }
}
