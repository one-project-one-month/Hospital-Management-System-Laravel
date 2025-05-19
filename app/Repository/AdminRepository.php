<?php


namespace App\Repository;

use App\Models\User;
use App\Models\DoctorProfile;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;

class AdminRepository{

    public function createReceptionist($data){
        $user=User::create($data);
        return $user;
    }

    public function createDoctor($data){
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        // Assign doctor role
        $user->assignRole(Role::findByName('doctor', 'api'));

      $doctor=  DoctorProfile::create([
            'user_id' => $user->id,
            'specialty' => $data['specialty'] ?? null,
            'license_number' => $data['license_number'],
            'education' => $data['education'],
            'experience_years' => $data['experience_years'],
            'availability'=>$data['availability'],
            'biography' => $data['biography'] ?? null,
            'phone' => $data['phone'] ?? null,
            'address' => $data['address'] ?? null,
        ]);

        return $doctor;
    }

}