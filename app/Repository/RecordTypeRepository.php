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

    public function findById($id)
    {
        return RecordType::findOrFail($id);
    }

    public function updateRecordType($data, $id)
    {
        $recordType = RecordType::findOrFail($id);
        $recordType->update($data);
        return $recordType;
    }

    public function deleteRecordType($id)
    {
        $recordType = RecordType::find($id);
        if (!$recordType) {
            return response()->json([
                'message' => 'Record Type not found.'
            ], 404);
        }

        $recordType->delete();
        return $recordType;
    }
}
