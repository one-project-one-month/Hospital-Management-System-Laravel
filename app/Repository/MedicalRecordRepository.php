<?php

namespace App\Repository;

use App\Models\MedicalRecord;

class MedicalRecordRepository
{
    public function store($data)
    {
        $record = MedicalRecord::create($data);

        if (!empty($data['medicines'])) {
            $medicineData = [];
            foreach ($data['medicines'] as $medicine) {
                $medicineData[$medicine['id']] = ['quantity' => $medicine['quantity']];
            }

            $record->medicines()->attach($medicineData);
        }

        return $record;
    }
}
