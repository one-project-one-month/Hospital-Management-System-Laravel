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
        if ($this->user->hasRole(usr\Role::USER)) {
            try {
                $patientProfile = $this->patientProfileRepository->getCurrentUserPatientProfile($this->user->id);
                return $this->success('success', ['user' => $this->user, 'patientProfile' => PatientProfileResource::make($patientProfile)], 'PatientProfile fetched successfully', 200);
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
    public function store(StorePatientProfileRequest $request)
    {
        if ($this->user->hasRole(usr\Role::USER)) {
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
    public function show(string $id)
    {
        if ($this->user->hasRole([usr\Role::USER, usr\Role::ADMIN, usr\Role::DOCTOR])) {
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
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
