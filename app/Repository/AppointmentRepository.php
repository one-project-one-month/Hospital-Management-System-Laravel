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

    public function getAppointmentsByDoctorAndDate($doctor_id, $appointment_date)
    {
        $appointments = Appointment::with(['patientProfile', 'doctorProfile'])->where('doctor_profile_id', $doctor_id)->where('appointment_date', $appointment_date)->get();
        return $appointments;
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

    public function updateAppointmentStatus($data, $id){
        $appointment = Appointment::findOrFail($id);

        if(!$appointment->isEmpty()){
            $appointment->update($data);
        }

        return $appointment;
    }

}
