<?php

namespace App\Repository;

use App\Models\User;

class ReceptionistRepository{

    public function getAllReceptionist(){
        $receptionists = User::whereHas('roles',function($query){
            $query->where('name','receptionist');
        })->get();
        return $receptionists;
    }

    public function getById($id){
        $receptionist=User::whereHas('roles',function($query){
            $query->where('name','receptionist');
        })->findOrFail($id);
        return $receptionist;
    }

    public function update($data,$id){
        $receptionist=User::where('id',$id)->first();

        $receptionist->update($data);
        return $receptionist;
    }

    public function delete(){
        $receptionist=User::where('id',$id)->first();
        $receptionist->delete();
        return $receptionist;
    }
}