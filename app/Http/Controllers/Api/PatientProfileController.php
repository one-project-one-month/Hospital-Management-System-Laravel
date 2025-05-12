<?php

namespace App\Http\Controllers\Api;

use App\Enums\User as usr;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponse;
use App\Repository\PatientProfileRepository;
use App\Http\Resources\PatientProfileResource;
use App\Http\Requests\PatientProfile\StorePatientProfileRequest;
use Illuminate\Support\Facades\Auth;
use App\Models\User;

class PatientProfileController extends Controller
{

    use HttpResponse;

    public function __construct(protected PatientProfileRepository $patientProfileRepository, protected User $user)
    {
        $this->user = Auth::user();
    }



    /**
     * Display All User Patients and user data
     */
    /**
     * @OA\Get(
     *     path="/api/v1/admin/patients",
     *     summary="Get list of patients",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of patients"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */

    public function getAllPatients()
    {


        if ($this->user->hasRole([usr\Role::ADMIN, usr\Role::DOCTOR])) {
            try {
                $allPatients = $this->patientProfileRepository->getAllPatients();
                return $this->success(
                    'success',
                    ['patients' => PatientProfileResource::collection($allPatients)],
                    'All Patient Profiles fetched successfully',
                    200
                );
            } catch (\Exception $e) {
                return $this->fail('fail', null, $e->getMessage(), 500);
            }
        }

        return $this->fail('fail', null, 'User is not authorized to access this resource', 401);
    }









    /**
     * Display Current User Patient Profile and user data
     */
    public function index(Request $request)
    {
        if ($this->user->hasRole(usr\Role::PATIENT)) {
            try {
                $patientProfile = $this->patientProfileRepository->getCurrentUserPatientProfile($this->user->id);
                return $this->success('success', [ 'patientProfile' => PatientProfileResource::collection($patientProfile)], 'PatientProfile fetched successfully', 200);
            } catch (\Exception $e) {
                // Catch all other exceptions
                return $this->fail('fail', null, $e->getMessage(), 500);
            }
        }

        return $this->fail('fail', null, 'User is not authorized to access this resource', 401);
    }





    /**
     * Store a newly created resource in storage.
     */

    /**
     * @OA\Post(
     *     path="/api/v1/patient-profile",
     *     summary="Create a patient profile",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "age", "date_of_birth", "gender", "address", "relation", "blood_type"},
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="age", type="integer", example=35),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1990-05-12"),
     *             @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
     *             @OA\Property(property="phone", type="string", example="09123456789"),
     *             @OA\Property(property="address", type="string", example="Yangon"),
     *             @OA\Property(property="relation", type="string", example="Father"),
     *             @OA\Property(property="blood_type", type="string", enum={"A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"}, example="O+")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Patient profile created"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */

    public function store(StorePatientProfileRequest $request)
    {
        if ($this->user->hasRole(usr\Role::PATIENT)) {
            try {
                $patientProfile = $request->validated();
                $patientProfile['user_id'] = $this->user->id;
                $createdPatientProfile = $this->patientProfileRepository->create($patientProfile);
                return $this->success('success', [PatientProfileResource::make($createdPatientProfile)], 'PatientProfile created successfully', 201);
            } catch (\Exception $e) {
                // Catch all other exceptions
                return $this->fail('fail', null, $e->getMessage(), 500);
            }
        }

        return $this->fail('fail', null, 'User is not authorized to access this resource', 401);
    }

    /**
     * Display the specified resource.
     */

    /**
     * @OA\Get(
     *     path="/api/v1/patient-profile/{id}",
     *     summary="Get a specific patient profile",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Patient ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Patient found"),
     *     @OA\Response(response=404, description="Patient not found")
     * )
     */

    public function show(string $id)
    {
        if ($this->user->hasRole([usr\Role::PATIENT, usr\Role::ADMIN, usr\Role::DOCTOR])) {
            try {
                $patientProfile = $this->patientProfileRepository->single($id);

                if (!$patientProfile) {
                    return $this->fail('fail', null, 'PatientProfile not found', 404);
                }
                return $this->success('success', [PatientProfileResource::make($patientProfile)], 'PatientProfile fetched successfully', 200);
            } catch (\Exception $e) {
                return $this->fail('fail', null, $e->getMessage(), 500);
            }
        }

        return $this->fail('fail', null, 'User is not authorized to access this resource', 401);
    }

    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/api/v1/patient-profile/{id}",
     *     summary="Update a patient profile",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Patient ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(property="name", type="string", example="John Doe"),
     *             @OA\Property(property="age", type="integer", example=36),
     *             @OA\Property(property="date_of_birth", type="string", format="date", example="1989-05-12"),
     *             @OA\Property(property="gender", type="string", enum={"male", "female", "other"}, example="male"),
     *             @OA\Property(property="phone", type="string", example="09876543210"),
     *             @OA\Property(property="address", type="string", example="Mandalay"),
     *             @OA\Property(property="relation", type="string", example="Brother"),
     *             @OA\Property(property="blood_type", type="string", enum={"A+", "A-", "B+", "B-", "AB+", "AB-", "O+", "O-"}, example="B+")
     *         )
     *     ),
     *     @OA\Response(response=200, description="Patient profile updated"),
     *     @OA\Response(response=404, description="Patient not found")
     * )
     */

    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     *     path="/api/v1/patient-profile/{id}",
     *     summary="Delete a patient profile",
     *     tags={"Patients"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         description="Patient ID",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Patient deleted"),
     *     @OA\Response(response=404, description="Patient not found")
     * )
     */

    public function destroy(string $id)
    {
        //
    }
}
