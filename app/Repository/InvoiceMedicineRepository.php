<?php

namespace App\Repository;

use App\Models\Invoice;
use App\Models\InvoiceMedicine;

class InvoiceMedicineRepository{

    public function getAllInvoiceMedicines($invoiceId){
        $invoiceMedicines = InvoiceMedicine::with('medicine')
        ->where('invoice_id', $invoiceId)
        ->get();
        return $invoiceMedicines;
    }

    public function createInvoiceMedicine($data){
        $invoice = Invoice::findOrFail($data['invoice_id']);

        $syncData = [];

        foreach ($data['medicines'] as $med) {
            $syncData[$med['id']] = ['quantity' => $med['quantity']];
        }

        $invoice->medicines()->sync($syncData);

        $invoiceMedicines = InvoiceMedicine::with(['medicine', 'invoice'])
        ->where('invoice_id', $data['invoice_id'])
        ->get();

        return $invoiceMedicines;
    }

}