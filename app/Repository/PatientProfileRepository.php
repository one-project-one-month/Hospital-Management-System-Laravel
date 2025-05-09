<?php

namespace App\Repository;

use App\Models\PatientProfile;

class PatientProfileRepository
{

    public function getCurrentUserPatientProfile($user_id)
    {
        $patientProfile=PatientProfile::where('user_id',$user_id)->get();
        return $patientProfile;
    }

    public function single($id)
    {
        $patientProfile=PatientProfile::find($id);
        return $patientProfile;
    }

    public function create($data)
    {
        $patientProfile=PatientProfile::create($data);
        return $patientProfile;
    }
}
