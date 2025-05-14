<?php

namespace App\Repository;

use App\Models\Appointment;

class AppointmentRepository
{


    public function getAllAppointments()
    {
        $appointments = Appointment::with(['PatientProfile.user', 'DoctorProfile.user'])->get();
        return $appointments;
    }

    private function createAppointment($data)
    {
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

}
