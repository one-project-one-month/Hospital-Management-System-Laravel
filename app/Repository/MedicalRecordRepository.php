<?php

namespace App\Repository;

use App\Http\Resources\MedicalRecordResource;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\MedicalRecordMedicine;
use App\Models\Medicine;
use Psy\CodeCleaner\ReturnTypePass;

class MedicalRecordRepository
{

    public function getMedicalRecord(Appointment $appointment)
    {
        if( ! $appointment){
            return null;
        }
        return MedicalRecord::where('appointment_id', $appointment->id)->first();
    }

    public function getAllMedicalRecords()
    {
        return MedicalRecord::all();
    }

    public function deleteMedicalRecord(Appointment $appointment){
        return MedicalRecord::where('appointment_id', $appointment->id)->delete();
    }

    public function updateMedicalRecord( $validated_data, Appointment $appointment )
    {   
        MedicalRecord::where('appointment_id', $appointment['id'])->update([
            'record_type_id' => $validated_data['record_type_id'],
            'title' => $validated_data['title'],
            'description' => $validated_data['description'],
            'recorded_at' => $validated_data['recorded_at'],
            'updated_at' => now(),
        ]);

        $medicalRecord = MedicalRecord::where('appointment_id', $appointment['id'])->first();
        $medicalRecordMedicines = MedicalRecordMedicine::where('medical_record_id', $medicalRecord['id'])->get();

        foreach($medicalRecordMedicines as $medicalRecordMedicine) {
            $medicine = Medicine::where('id', $medicalRecordMedicine['medicine_id'])->first();
            $adding_stock = $medicine['stock'] + $medicalRecordMedicine['quantity'];
            $medicine->update([
                'stock' => $adding_stock,
            ]);
        }

        MedicalRecordMedicine::where('medical_record_id', $medicalRecord->id)->delete();

        $totalMedicinePrice=0;
        $attachData=[];

       foreach ($validated_data['medicines'] as $item) {
        $medicine=Medicine::findOrFail($item['medicine_id']);
        $quantity=$item['quantity'];
        $stock_left = $medicine['stock'] - $quantity;
        Medicine::where('id', $medicine->id)->update(['stock' => $stock_left]); 
        $total=$medicine->price * $quantity;

        $totalMedicinePrice+=$total;
        $attachData[$medicine->id]=['quantity'=>$quantity];
       }

       $medicalRecord->medicines()->attach($attachData);

       $medicalRecord->update([
        'medicine_price'=>$totalMedicinePrice
       ]);

       return $medicalRecord;
    }

    public function store($data)
    {
        $medicalRecord = MedicalRecord::where('appointment_id', $data['appointment_id'])->first();

        if ($medicalRecord) {
            return null;
        }

        $record = MedicalRecord::create($data);

        $totalMedicinePrice=0;
        $attachData=[];

       foreach ($data['medicines'] as $item) {
        $medicine=Medicine::findOrFail($item['medicine_id']);
        $quantity=$item['quantity'];
        $stock_left = $medicine['stock'] - $quantity;
        Medicine::where('id', $medicine->id)->update(['stock' => $stock_left]);
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
