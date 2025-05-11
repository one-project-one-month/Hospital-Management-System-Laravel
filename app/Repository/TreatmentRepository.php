<?php

namespace App\Repository;

use App\Enums\User as usr;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\User;

class TreatmentRepository
{
    public function getAllTreatments(User $user){

        if($user->hasRole(usr\Role::USER))
        {
            $treatments = Treatment::where('user_id', $user->id)->get();
            return $treatments;
        }
        if($user->hasRole(usr\Role::RECEPTIONIST)){
            $treatments = Treatment::all();
            return $treatments;
        }
        if ($user->hasRole(usr\Role::DOCTOR)) {
        return Treatment::whereHas('appointment', function ($query) use ($user) {
            $query->where('doctor_profile_id', $user->id);
        })->get();
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
