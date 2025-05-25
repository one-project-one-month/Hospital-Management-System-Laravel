<?php

namespace App\Repository;

use App\Http\Controllers\Api\AppointmentController;
use App\Models\Appointment;
use App\Models\PatientProfile;
use function PHPUnit\Framework\isEmpty;

class AppointmentRepository
{


    public function getAllAppointments()
    {
        $appointments = Appointment::with(['patientProfile', 'patientProfile'])->get();
        return $appointments;
    }

    public function getAppointmentsByDoctorAndDate($filters)
    {
        $appointment=Appointment::with(['patientProfile','doctorProfile'])->filterBy($filters)->get();
        return $appointment;
    }

    private function createAppointment($data)
    {
        dd($data);
        $appointment = Appointment::create($data);
        return $appointment;
    }

    // Patient booking (uses createAppointment)
    public function bookAsPatient($data)
    {
        return $this->createAppointment($data);
    }

    // Receptionist booking (uses createAppointment)
    public function bookAsReceptionist($data)
    {
        return $this->createAppointment($data);
    }


    //update appointment
    public function updateAppointment($data,$id){
        $appointment = Appointment::findOrFail($id);
        $appointment->update($data);
        return $appointment;
    }

    public function findById(Appointment $appointment){
        return $appointment;
    }



    // Appointments for Patients
    public function appointmentForPatient($id)
    {
        $patientAppointment = Appointment::with(['PatientProfile.user', 'DoctorProfile.user'])->whereIn('patient_profile_id', $id)->get();
        return $patientAppointment;
    }

    // Appointments for Doctors
    public function appointmentForDoctor($id)
    {
        $patientAppointment = Appointment::with(['PatientProfile', 'DoctorProfile'])->where('doctor_profile_id', $id)->get();
        return $patientAppointment;
    }

    public function getPatientFormAppointment(){
        $user=auth()->user();
        $patientProfile = PatientProfile::where('user_id', $user->id)->first();
        $appointment=Appointment::where('patient_profile_id',$patientProfile->id)->get();
        return $appointment;
    }

    public function getAppointmentById($id){
        $appointment = Appointment::findOrFail($id);
        return $appointment;
    }

    public function updateAppointmentStatus($id){
        $appointment = Appointment::findOrFail($id);

        if($appointment){
            $appointment->update(
                [
                    'status' => 'confirmed'
                ]
            );
        }

        return $appointment;
    }

    public function deleteAppointmentStatus($id){
        $appointment = Appointment::findOrFail($id);

        if($appointment){
            $appointment->update([
                'status' => 'cancelled'
            ]);
        }

        return $appointment;
    }

}
