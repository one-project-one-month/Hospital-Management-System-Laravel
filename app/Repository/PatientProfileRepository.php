<?php

namespace App\Repository;

use App\Models\PatientProfile;

class PatientProfileRepository{

    public function upgradePatient($validator){
        $validated_data = PatientProfile::create($validator);
        return $validated_data;
    }

}