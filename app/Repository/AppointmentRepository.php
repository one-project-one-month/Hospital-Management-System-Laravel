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

}
