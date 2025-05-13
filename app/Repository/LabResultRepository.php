<?php

namespace App\Repository;

use App\Models\LabResult;

class LabResultRepository
{
    public function getByPatientId($patientId)
    {
        $labResults = LabResult::whereHas('appointment', function ($query) use ($patientId) {
            $query->where('patient_profile_id', $patientId);
        })->get();

        return $labResults;
    }

    public function createLabResult(array $data)
    {
        return LabResult::create($data);
    }

    public function getLabResultById($id)
    {
        return LabResult::findOrFail($id);
    }

    public function updateLabResult(array $data, $id)
    {
        $labResult=LabResult::findOrFail($id);
        if (!$labResult) {
            return response()->json([
                'message' => 'Lab result not found.'
            ], 404);
        }

        $labResult->update($data);
        return $labResult;
    }

    public function deleteLabResult($id)
    {
        $labResult = LabResult::find($id);
        if (!$labResult) {
            return response()->json([
                'message' => 'Lab result not found.'
            ], 404);
        }

        $labResult->delete();
        return $labResult;
    }


}
