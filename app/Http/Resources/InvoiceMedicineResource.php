<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class InvoiceMedicineResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'invoice_id' => $this->invoice_id,
            'invoice_name' => $this->invoice->name,
            'amount' => $this->amount,
            'status' => $this->status,
            'medicines' => $this->medicine->map(function ($item) {
                return [
                    'id' => $item->medicine->id,
                    'name' => $item->medicine->name,
                    'stock' => $item->medicine->stock,
                    'expired_at' => $item->medicine->expired_at,
                ];
            }),
        ];
    }
}
