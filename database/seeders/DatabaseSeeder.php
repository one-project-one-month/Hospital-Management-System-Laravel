<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Treatment;
use App\Models\Appointment;
use App\Models\DoctorProfile;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use App\Models\DoctorSchedule;
use App\Models\PatientProfile;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::create(['name' => 'admin','guard_name'=>'api']);
        Role::create(['name' => 'user','guard_name'=>'api']);
        Role::create(['name' => 'patient','guard_name'=>'api']);
        Role::create(['name' => 'doctor','guard_name'=>'api']);

        $this->call(MedicineSeeder::class);
        // User::factory(10)->create();

       $user= User::create([
            'name' => 'Test User',
            'email' => 'test@example.com',
            'password' => bcrypt('password'),
        ]);


        $user->assignRole(Role::findByName('user', 'api'));

        $patient=PatientProfile::create([
            'user_id' => $user->id,
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => '123 Test Street',
            'relation' => 'self',
            'blood_type' => 'O+'
        ]);

        $doctorUser = User::create([
            'name' => 'Dr. John Smith',
            'email' => 'doctor@example.com',
            'password' => bcrypt('password'),
        ]);

        $doctorUser->assignRole(Role::findByName('doctor', 'api'));

      $doctor= DoctorProfile::create([
            'user_id' => $doctorUser->id,
            'specialty' => json_encode(['Cardiology', 'Internal Medicine']),
            'license_number' => 'MD123456',
            'education' => 'M.D. from Harvard Medical School',
            'experience_years' => 15,
            'biography' => 'Board certified cardiologist with 15 years of experience in treating cardiovascular diseases.',
            'phone' => '9876543210',
            'address' => '456 Medical Center Drive, Suite 100'
        ]);

        // Create doctor schedules for Dr. John Smith
        $weekdays = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        foreach ($weekdays as $weekday) {
            DoctorSchedule::create([
                'doctor_profile_id' => $doctor->id,
                'weekday' => $weekday,
                'start_time' => '09:00:00',
                'end_time' => '17:00:00',
                'is_available' => true
            ]);
        }

        // Create sample appointments
        Appointment::create([
            'patient_profile_id' => $patient->id,
            'doctor_profile_id' => $doctor->id,
            'appointment_date' => now()->subDays(5)->toDateString(),
            'appointment_time' => '11:15:00',
            'status' => 'pending',
            'notes' => 'Initial consultation completed',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5)
        ]);











    }
}
