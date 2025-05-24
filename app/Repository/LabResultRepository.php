<?php

namespace App\Repository;

use App\Models\LabResult;

class LabResultRepository
{
    public function getByAppointmentId($appointmentId)
    {
        return LabResult::where('appointment_id', $appointmentId)->get();
    }

    public function createLabResult(array $data)
    {
        return LabResult::create($data);
    }

    public function getLabResultById($id, $appointmentId)
    {
        $labResult = LabResult::where('id', $id)->where('appointment_id', $appointmentId)->first();
        if (!$labResult) {
            return null;
        }

        return $labResult;
    }

    public function updateLabResult(array $data, $id)
    {
        $labResult=LabResult::findOrFail($id);

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
