<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Traits\HttpResponse;
use App\Repository\PatientProfileRepository;
use App\Http\Resources\PatientProfileResource;
use App\Http\Requests\PatientProfile\StorePatientProfileRequest;
use Illuminate\Validation\ValidationException;

class PatientProfileController extends Controller
{

    use HttpResponse;

    public function __construct(protected PatientProfileRepository $patientProfileRepository) {}

    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StorePatientProfileRequest $request)
    {
        try {
            $user_id=$request->user()->id;
            $patientProfile=$request->validated();
            $patientProfile['user_id']=$user_id;
            $createdPatientProfile=$this->patientProfileRepository->create($patientProfile);
            return $this->success('success',['patientProfile'=>PatientProfileResource::make($createdPatientProfile)],'PatientProfile created successfully',201);
        } catch (\Exception $e) {
            // Catch all other exceptions
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
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
