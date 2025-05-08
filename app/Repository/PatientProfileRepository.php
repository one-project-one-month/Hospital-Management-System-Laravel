<?php

namespace App\Repository;

use App\Models\PatientProfile;

class PatientProfileRepository
{
    public function create($data)
    {
        $patientProfile=PatientProfile::create($data);
        return $patientProfile;
    }
}
