<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Repository\AppointmentRepository;

class AppointmentController extends Controller
{
    use HttpResponse;

    protected $appointmentRepo;

    public function __construct(AppointmentRepository $appointmentRepo){
        $this->appointmentRepo=$appointmentRepo;
    }

    public function index(){
        try {
           $appointments= $this->appointmentRepo->getAllAppointments();
        } catch (\Exception $e) {

        }
    }

    public function createAppointmentFromPatient(StoreAppointmentRequest $request){
        try {
            $validatedData=$request->validated();
            $appointment=$this->appointmentRepo->bookAsPatient($validatedData);
            $createdAppointment=Appointment::where('id',$appointment->id)->first();
            return $this->success('success',['appointment'=>AppointmentResource::make($createdAppointment)],'Appointment Created Successfully',201);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    public function receptionistBookAppointment(StoreAppointmentRequest $request){
        try {
            $validatedData=$request->validated();
            $appointment=$this->appointmentRepo->bookAsReceptionist($validatedData);
            $appointmentCreated=Appointment::where('id',$appointment->id)->first();
            return $this->success('success',['appointment'=>AppointmentResource::make($appointmentCreated)],'Appointment Created Successfully',201);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }
}
