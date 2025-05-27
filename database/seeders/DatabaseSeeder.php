<?php

namespace Database\Seeders;

use App\Models\User;
use App\Models\Appointment;
use App\Models\DoctorProfile;
use App\Models\DoctorSchedule;
use App\Models\PatientProfile;
use App\Models\RecordType;
use App\Models\Treatment;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {

        Role::create(['name' => 'admin', 'guard_name' => 'api']);
        Role::create(['name' => 'patient', 'guard_name' => 'api']);
        Role::create(['name' => 'doctor', 'guard_name' => 'api']);
        Role::create(['name' => 'receptionist', 'guard_name' => 'api']);

        $this->call(MedicineSeeder::class);

        RecordType::create([
            'name'=>'symptom',
            'text'=>'blah bgdfd',
        ]);

        RecordType::create([
            'name'=>'diagnosis',
            'text'=>'blah bgdfd',
        ]);

        RecordType::create([
            'name'=>'allergy',
            'text'=>'blah bgdfd',
        ]);

        $admin = User::create([
            'name' => 'Admin',
            'email' => 'admin@gmail.com',
            'password' => bcrypt('password'),
        ]);


        $admin->assignRole(Role::findByName('admin', 'api'));
        // User::factory(10)->create();
        $user = User::create([
            'name' => 'Patient User',
            'email' => 'patient@gmail.com',
            'password' => bcrypt('password'),
        ]);


        $user->assignRole(Role::findByName('patient', 'api'));

        $patient = PatientProfile::create([
            'user_id' => $user->id,
            'name' => 'Mg Mg',
            'age' => 50,
            'date_of_birth' => '1990-01-01',
            'gender' => 'male',
            'phone' => '1234567890',
            'address' => '123 Test Street',
            'relation' => 'self',
            'blood_type' => 'O+'
        ]);
        // $patient->assignRole(Role::findByName('patient','api'));

        $doctorUser = User::create([
            'name' => 'Dr. John Smith',
            'email' => 'doctor@example.com',
            'password' => bcrypt('password'),
        ]);

        $doctorUser->assignRole(Role::findByName('doctor', 'api'));


        $doctor = DoctorProfile::create([
            'user_id' => $doctorUser->id,
            'specialty' => json_encode(['Cardiology', 'Internal Medicine']),
            'license_number' => 'MD123456',
            'education' => 'M.D. from Harvard Medical School',
            'experience_years' => 15,
            'availability' => [
                'Mon' => ['14:00', '16:00'],
                'Wed' => ['10:00', '11:00', '15:00'],
                'Fri' => ['09:00', '13:00'],
            ],
            'biography' => 'Board certified cardiologist with 15 years of experience in treating cardiovascular diseases.',
            'phone' => '9876543210',
            'address' => '456 Medical Center Drive, Suite 100'
        ]);

        $receptionistUser = User::create([
            'name' => 'Receptionist',
            'email' => 'reception@example.com',
            'password' => bcrypt('password'),
        ]);

        $receptionistUser->assignRole(Role::findByName('receptionist', 'api'));

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
        $appointment = Appointment::create([
            'patient_profile_id' => $patient->id,
            'doctor_profile_id' => $doctor->id,
            'appointment_date' => now()->subDays(5)->toDateString(),
            'appointment_time' => '11:15:00',
            'status' => 'pending',
            'notes' => 'Initial consultation completed',
            'created_at' => now()->subDays(5),
            'updated_at' => now()->subDays(5)
        ]);

        Treatment::create([
            'appointment_id' => $appointment->id,
            'title' => 'Blood Pressure Management',
            'description' => 'Prescribed medication to manage high blood pressure and advised lifestyle changes.',
            'start_date' => now()->subDays(5)->toDateString(),
            'end_date' => now()->addDays(10)->toDateString(),
        ]);













    }
}
