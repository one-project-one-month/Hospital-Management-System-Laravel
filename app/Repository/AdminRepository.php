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

    public function createUser($data){
        $user = User::create([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);
        return $user;
    }

    public function createDoctor($data){

      $doctor=  DoctorProfile::create([
            'user_id' => $data['user_id'],
            'specialty' => $data['specialty'],
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

    public function updateDoctor($data, $id){
        $doctor = DoctorProfile::find($id);

        $user = User::find($doctor->user_id);
        $user->update([
            'name' => $data['name'],
            'email' => $data['email'],
            'password' => Hash::make($data['password']),
        ]);

        $doctor->update([
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

    public function deleteDoctor($id){
        $doctor = DoctorProfile::find($id);

        $user = User::find($doctor->user_id);
        $user->delete();
         $doctor->delete();
        return $doctor;
    }

}
