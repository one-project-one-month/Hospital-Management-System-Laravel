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

    /**
     * @OA\Get(
     *     path="/appointments",
     *     summary="Get a list of all appointments",
     *     tags={"Appointments"},
     *     @OA\Response(
     *         response=200,
     *         description="List of appointments",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="doctor_profile_id", type="integer", example=5),
     *                 @OA\Property(property="appointment_date", type="string", format="date", example="2025-06-15"),
     *                 @OA\Property(property="appointment_time", type="string", example="10:00"),
     *                 @OA\Property(property="status", type="string", example="confirmed"),
     *                 @OA\Property(property="notes", type="string", example="Follow-up visit for test results.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Internal server error"
     *     )
     * )
     */

    public function index(){
        try {
            if(request()->filled('doctor_id', 'appointment_date')){
                $appointments = $this->appointmentRepo->getAppointmentsByDoctorAndDate(request()->doctor_id, request()->appointment_date);

                return $this->success('success', ['appointment' => AppointmentResource::collection($appointments)], 'Appointments', 200);
            }

            $appointments = $this->appointmentRepo->getAllAppointments();
            return $this->success('success', ['appointment' => AppointmentResource::collection($appointments)], 'Appointments', 200);
        } catch (\Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * @OA\Post(
     *     path="/appointments/patient",
     *     summary="Create a new appointment from the patient",
     *     tags={"Appointments"},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"patient_profile_id", "doctor_profile_id", "appointment_date", "appointment_time", "status"},
     *             @OA\Property(property="patient_profile_id", type="integer", example=1),
     *             @OA\Property(property="doctor_profile_id", type="integer", example=5),
     *             @OA\Property(property="appointment_date", type="string", format="date", example="2025-06-10"),
     *             @OA\Property(property="appointment_time", type="string", example="09:30"),
     *             @OA\Property(property="status", type="string", example="pending"),
     *             @OA\Property(property="notes", type="string", example="First-time consultation.")
     *         )
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Appointment created successfully",
     *         @OA\JsonContent(
     *             @OA\Property(property="message", type="string", example="Appointment created successfully"),
     *             @OA\Property(property="appointment_id", type="integer", example=123)
     *         )
     *     ),
     *     @OA\Response(
     *         response=400,
     *         description="Bad request"
     *     )
     * )
     */

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

     /**
     * @OA\Post(
     *     path="/api/v1/appointments/receptionist",
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

    public function receptionistBookAppointment(StoreAppointmentRequest $request){
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



    /**
     * @OA\Get(
     *     path="/api/v1/appointments/doctor",
     *     summary="Get all appointments for the doctor",
     *     tags={"Appointments"},
     *     @OA\Response(
     *         response=200,
     *         description="List of doctor appointments",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 type="object",
     *                 @OA\Property(property="patient_profile_id", type="integer", example=1),
     *                 @OA\Property(property="doctor_profile_id", type="integer", example=2),
     *                 @OA\Property(property="appointment_date", type="string", format="date", example="2025-06-01"),
     *                 @OA\Property(property="appointment_time", type="string", example="14:00"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="notes", type="string", example="Patient has a history of allergies.")
     *             )
     *         )
     *     )
     * )
     */
    public function getDoctorAppointments()
    {
        $user=auth()->user();
        $doctorId=$user->doctorProfile->id;
        $appointment = $this->appointmentRepo->appointmentForDoctor($doctorId);
        if ($appointment->isEmpty()) {
            return $this->fail('fail', null, 'No appointments found for this doctor', 404);
        }
        return $this->success('success', [
            'appointments' => AppointmentResource::collection($appointment)
        ], 'Appointments retrieved successfully', 200);
    }

     public function updateReceptionistBookAppointment(UpdateAppointmentRequest $request, Appointment $appointment){
        if($this->user->hasRole(usr\Role::RECEPTIONIST)){
            $validatedData = $request->validated();
            $appointment = $this->appointmentRepo->updateAppointment($validatedData,$appointment->id);
            $updateAppointment = $this->appointmentRepo->findById($appointment);
            return $this->success('success',AppointmentResource::make($updateAppointment),'Status Updated Successfully',201);
        }
    }

    /**
     * @OA\Get(
     *     path="api/v1/patients/appointments",
     *     summary="Get all appointments for a specific patient",
     *     tags={"Appointments"},
     *     @OA\Parameter(
     *         name="patient",
     *         in="path",
     *         description="Patient profile ID",
     *         required=true,
     *         @OA\Schema(type="integer", example=1)
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="List of patient appointments",
     *         @OA\JsonContent(
     *             type="array",
     *             @OA\Items(
     *                 @OA\Property(property="doctor_profile_id", type="integer", example=5),
     *                 @OA\Property(property="appointment_date", type="string", format="date", example="2025-06-10"),
     *                 @OA\Property(property="appointment_time", type="string", example="14:00"),
     *                 @OA\Property(property="status", type="string", example="pending"),
     *                 @OA\Property(property="notes", type="string", example="Check blood pressure follow-up.")
     *             )
     *         )
     *     ),
     *     @OA\Response(
     *         response=404,
     *         description="Patient not found or no appointments"
     *     )
     * )
     */

    public function getPatientFormAppointment(){
        try {
           $patient= $this->appointmentRepo->getPatientFormAppointment();
           return $this->success('success',AppointmentResource::collection($patient),'showed  Successfully',200);

        } catch (\Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);

        }
    }
}
