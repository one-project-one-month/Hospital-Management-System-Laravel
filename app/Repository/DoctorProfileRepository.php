<?php

namespace App\Repository;

use App\Models\DoctorProfile;
use App\Models\Medicine;

class DoctorProfileRepository
{
    public function getAllDoctorProfiles()
    {
        $doctors = DoctorProfile::with('user')->paginate(20);
        return $doctors;
    }
}
