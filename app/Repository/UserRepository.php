<?php

namespace App\Repository;

use App\Models\User;

class UserRepository{

    public function createUser($data){
        $user=User::create([
            'name'=>$data['name'],
            'email'=>$data['email'],
            'password'=>bcrypt($data['password']),
        ]);
        return $user;
    }

    public function loginUser($data){
        $user=User::where('email',$data['email'])->first();
        if(!$user || !password_verify($data['password'], $user->password)){
            return null;
        }
        return $user;
    }
}
