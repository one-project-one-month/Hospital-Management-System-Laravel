<?php

namespace App\Repository;

use App\Models\Treatment;

class TreatmentRepository
{
    public function getAllTreatments(){
        $treatments = Treatment::all();
        return $treatments;
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
