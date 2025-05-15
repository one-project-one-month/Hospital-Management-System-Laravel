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

    /**
     * @OA\Get(
     *     path="api/v1/appointments/patient",
     *     summary="List all medicines",
     *     tags={"medicine"},
     *     @OA\Response(
     *         response=200,
     *         description="Successful response"
     *     )
     * )
     */

    public function index(){
        try {
           $appointments= $this->appointmentRepo->getAllAppointments();
        } catch (\Exception $e) {

        }
    }


     /**
     * @OA\Post(
     *     path="/api/v1/appointments/patient",
     *     summary="Create a appointment from patient",
     *     tags={"Appointments-Patients"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"patient_profile_id", "doctor_profile_id", "appointment_date","appointment_time","status","notes"},
     *            @OA\Property(property="patient_profile_id", type="string", format="uuid", example="e7d4a5ed-b4b2-43b1-b7b9-128aa07539b2"),
     *  @OA\Property(property="doctor_profile_id", type="string", format="uuid", example="e7d4a5ed-b4b2-43b1-b7b9-128aa07539b2"),
     *             @OA\Property(property="appointment_date", type="string", format="date", example="2025-12-31"),
     *            @OA\Property(property="appointment_time", type="string", format="time", example="14:30:00"),
       *
*@OA\Property(property="notes", type="string", format="text", example="This is a note for the appointment."),

     *         )
     *     ),
     *     @OA\Response(response=201, description="Medication created"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */

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

    public function getDoctorAppointments()
    {
        $user=auth()->user();
        $doctorId=$user->doctorProfile->id;
        $appointments=Appointment::where('doctor_profile_id',$doctorId)->get();
        if ($appointments->isEmpty()) {
            return $this->fail('fail', null, 'No appointments found for this doctor', 404);
        }
        return $this->success('success', [
            'appointments' => AppointmentResource::collection($appointments)
        ], 'Appointments retrieved successfully', 200);
    }
}
