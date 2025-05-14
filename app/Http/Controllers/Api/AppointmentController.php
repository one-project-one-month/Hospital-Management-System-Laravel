<?php

namespace App\Http\Controllers\Api;

use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Requests\Appointment\StoreAppointmentRequest;
use App\Http\Resources\AppointmentResource;
use App\Models\Appointment;
use App\Repository\AppointmentRepository;
use App\Http\Requests\PatientProfile\StorePatientProfileRequest;
use App\Models\PatientProfile;

class AppointmentController extends Controller
{
    use HttpResponse;

    protected $appointmentRepo;

    public function __construct(AppointmentRepository $appointmentRepo)
    {
        $this->appointmentRepo = $appointmentRepo;
    }

    // get all appointments
    public function index()
    {
        try {
            $appointments = $this->appointmentRepo->getAllAppointments();
            return $this->success('success', ['appointment' => AppointmentResource::collection($appointments)], 'Get all appointments', 201);
        } catch (\Exception $e) {
            return $this->fail('fail', $e->getMessage(), 'No appointments found', 404);
        }
    }

    public function createAppointmentFromPatient(StoreAppointmentRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $appointment = $this->appointmentRepo->bookAsPatient($validatedData);
            $createdAppointment = Appointment::where('id', $appointment->id)->first();
            return $this->success('success', ['appointment' => AppointmentResource::make($createdAppointment)], 'Appointment Created Successfully', 201);
        } catch (\Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function receptionistBookAppointment(StoreAppointmentRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $appointment = $this->appointmentRepo->bookAsReceptionist($validatedData);
            $appointmentCreated = Appointment::where('id', $appointment->id)->first();
            return $this->success('success', ['appointment' => AppointmentResource::make($appointmentCreated)], 'Appointment Created Successfully', 201);
        } catch (\Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    public function appointmentReadPatient($role)
    {
        try {
            $user = auth()->user();

            if(!$user->hasRole($role)){
                return $this->fail('fail', null, 'Unauthorized access for this role', 403);
            }

            if ($role === 'patient') {
                // get logged-in user
                $patientProfiles = $user->patientProfiles;

                // return an array of patientProfiles depending on id
                $patientProfileIds = $patientProfiles->pluck('id');

                // Pass the IDs to the repo
                $appointments = $this->appointmentRepo->appointmentForPatient($patientProfileIds);

            } else if ($role === 'doctor') {
                $doctorProfile = $user->doctorProfile();
                $doctorProfileId = $doctorProfile->pluck('id');
                $appointments = $this->appointmentRepo->appointmentForDoctor($doctorProfileId);

            }else if($role === 'receptionist'){
                return $this->index();

            }else{
                return $this->fail('fail', null, 'Invalid Role', 400);
            }

            if(!$appointments || $appointments->isEmpty()){
                return $this->fail('fail', null, 'No appointments found', 404);
            }

            return $this->success('success', ['appointment' => AppointmentResource::collection($appointments)], $user->hasRole('patient') ? 'Appointment for Patient' : 'Appointment for Doctor', 200);
        } catch (\Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }
}
