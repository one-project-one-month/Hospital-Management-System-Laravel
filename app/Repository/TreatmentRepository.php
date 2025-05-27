<?php

namespace App\Repository;

use App\Enums\User as usr;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\User;

class TreatmentRepository
{
    public function getAllTreatments($appointment){
        $treatments = Treatment::where('appointment_id',$appointment->id)->first();
        return $treatments;
    }


    public function createTreatment($data){

        $treatment = Treatment::create($data);
        return $treatment;
    }

    public function findById(Appointment $appointment, Treatment $treatment){
        $treatment = Treatment::where('appointment_id',$appointment->id)->first();
        return $treatment;
    }

    public function updateTreatment($data,$treatment){
        $treatment->update([
            'end_date'=>$data['end_date']
        ]);
        return $treatment;
    }

    public function getTreatments(){
        $treatments=Treatment::get();
        return $treatments;
    }

}
