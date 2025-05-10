<?php

namespace App\Repository;

use App\Models\Appointment;

class AppointmentRepository{


    public function getAllAppointments(){
        $appointments=Appointment::with('')->get();
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

}