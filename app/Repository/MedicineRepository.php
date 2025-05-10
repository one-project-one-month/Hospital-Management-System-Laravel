<?php

namespace App\Repository;

use App\Models\Medicine;

class MedicineRepository{

    public function getAllMedicines($request){
        $medicines=Medicine::all();
        return $medicines;
    }

    public function createMedicine($data){
        $medicine=Medicine::create($data);
        return $medicine;
    }

    public function findById($id){
        $medicine=Medicine::findOrFail($id);
        return $medicine;
    }

    public function updateMedicine($data,$id){
        $medicine=Medicine::findOrFail($id);
        $medicine->update($data);
        return $medicine;
    }

    public function deleteMedicine($id){

        $medicine = Medicine::find($id);
        if (!$medicine) {
            return response()->json([
                'message' => 'Medicine not found.'
            ], 404);
        }

        $medicine->delete();
        return $medicine;
        }


}