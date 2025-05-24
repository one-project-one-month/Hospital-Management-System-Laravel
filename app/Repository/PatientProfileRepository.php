<?php

namespace App\Repository;

use App\Models\PatientProfile;
use App\Models\User;

class PatientProfileRepository
{

    public function getAllPatients(){
        return  PatientProfile::all();
    }

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

    public function getMyPatientAccounts(){
        $user=auth()->user();
        $patientProfiles=PatientProfile::where('user_id',$user->id)->get();
        return $patientProfiles;
    }

    public function getUsers(){
        // $patients = User::role('patient')->with('patientProfile')->get();
        $patients = User::with('patientProfiles')->whereHas('roles', function($query){
            $query->where('name', 'patient');
        })->get();
        return $patients;
    }
}
