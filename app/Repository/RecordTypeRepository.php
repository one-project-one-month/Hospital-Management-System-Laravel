<?php

namespace App\Repository;

use App\Models\RecordType;

class RecordTypeRepository
{
    public function getAllRecordTypes($request)
    {
        return RecordType::all();
    }

    public function createRecordType($data)
    {
        return RecordType::create($data);
    }

    public function findById(RecordType $recordType)
    {

        return $recordType;
    }

    public function updateRecordType($data, $id)
    {
        $recordType = RecordType::findOrFail($id);
        $recordType->update($data);
        return $recordType;
    }
}
