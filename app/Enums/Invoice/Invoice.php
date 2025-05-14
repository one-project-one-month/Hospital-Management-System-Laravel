<?php

namespace App\Enums\Invoice;

enum Invoice: string{
    case Paid = 'paid';
    case Unpaid = 'unpaid';

    public function label() : string{
        return match($this){
            Invoice::Paid => 'Paid',
            Invoice::Unpaid => 'Unpaid'
        };
    }
}
