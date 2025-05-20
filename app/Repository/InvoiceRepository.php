<?php

namespace App\Repository;

use App\Models\Invoice;

class InvoiceRepository{
    public function create($data){
        $invoice = Invoice::create($data);
        return $invoice;
    }

    public function getAllInvoice(){
        $invoice = Invoice::all();
        return $invoice;
    }

    public function findById($id){
        $invoice = Invoice::findOrFail($id);
        return $invoice;
    }

    public function updateInvoice($data, $id){
        $invoice = Invoice::findOrFail($id);
        $invoice->update($data);
        return $invoice;
    }

    


}
