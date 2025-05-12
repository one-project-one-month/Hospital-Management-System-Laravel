<?php

namespace App\Repository;

use App\Enums\User as usr;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\User;

class TreatmentRepository
{
    public function getAllTreatments(User $user){

      if ($user->hasRole(usr\Role::PATIENT)) {
        $appointment = Appointment::where('patient_profile_id', $user->patientProfile->id)->first();

        if ($appointment) {
            $treatment = Treatment::where('appointment_id', $appointment->id)->first();
            return $treatment;
        }

        return null; // no appointment found
    }
        if($user->hasRole(usr\Role::RECEPTIONIST)){
            $treatments = Treatment::all();
            return $treatments;
        }
        if ($user->hasRole(usr\Role::DOCTOR)) {
         $appointment = Appointment::where('doctor_profile_id', $user->doctorProfile->id)->first();

        if ($appointment) {
            $treatment = Treatment::where('appointment_id', $appointment->id)->first();
            return $treatment;
        }

        return null; // no appointment found
        }
    }


    public function createTreatment($data){
        // dd($data);
        $treatment = Treatment::create($data);
        return $treatment;
    }

    public function findById($id){
        $treatment = Treatment::findOrFail($id);
        return $treatment;
    }

    public function updateTreatment($data,$id){
        // dd($data);
        $treatment = Treatment::findOrFail($id);
        $treatment->update($data);
        return $treatment;
    }

    public function destroyTreatment($id){
        $treatment = Treatment::find($id);
        $treatment->delete();
        return $treatment;
    }

}
