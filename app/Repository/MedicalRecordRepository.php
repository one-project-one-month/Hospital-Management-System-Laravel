<?php

namespace App\Repository;

use App\Models\MedicalRecord;
use App\Models\Medicine;

class MedicalRecordRepository
{
    public function store($data)
    {
        $record = MedicalRecord::create($data);

        $totalMedicinePrice=0;
        $attachData=[];

       foreach ($data['medicines'] as $item) {
        $medicine=Medicine::findOrFail($item['medicine_id']);
        $quantity=$item['quantity'];
        $total=$medicine->price * $quantity;

        $totalMedicinePrice+=$total;
        $attachData[$medicine->id]=['quantity'=>$quantity];
       }

       $record->medicines()->attach($attachData);

       $record->update([
        'medicine_price'=>$totalMedicinePrice
       ]);

       return $record;
    }
}
