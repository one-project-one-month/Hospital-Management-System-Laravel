<?php

namespace App\Repository;

use App\Http\Resources\MedicalRecordResource;
use App\Models\MedicalRecord;
use App\Models\Medicine;
use Psy\CodeCleaner\ReturnTypePass;

class MedicalRecordRepository
{

    public function getAllMedicalRecords()
    {
        return MedicalRecordResource::collection(MedicalRecord::all());
    }

    public function getMedicalRecord(MedicalRecord $medicalRecord)
    {
        return $medicalRecord;
    }

    public function deleteMedicalRecord(MedicalRecord $medicalRecord){
        return $medicalRecord->delete();
    }

    public function updateMedicalRecord( $validated_data, MedicalRecord $medicalRecord )
    {   
        $id = $medicalRecord->update($validated_data);
        return MedicalRecord::findOrFail($id);
    }

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
