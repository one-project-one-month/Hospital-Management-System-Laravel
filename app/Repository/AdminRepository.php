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
            'speciality' => $data['speciality'] ?? null,
            'license_number' => $data['license_number'],
            'education' => $data['education'],
            'experience_years' => $data['experience_years'],
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
            'speciality' => $data['speciality'] ?? null,
            'license_number' => $data['license_number'],
            'education' => $data['education'],
            'experience_years' => $data['experience_years'],
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
        // $doctor->delete();
        return $doctor;
    }

}
