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

    public function getMyDoctorProfile(){
        $user=auth()->user();
        $doctor=DoctorProfile::where('id',$user->id)->first();
       return $doctor;
    }

    public function getById($id){
        $doctor=DoctorProfile::where('id',$id)->first();
        return $doctor;
    }
}
