<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LabResult\StoreLabResultRequest;
use App\Http\Resources\LabResultResource;
use App\Models\User;
use App\Models\LabResult;
use App\Repository\LabResultRepository;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;

class LabResultController extends Controller
{
    use HttpResponse;

    protected $labResultRepositry;

    public function __construct(LabResultRepository $labResultRepository)
    {
        $this->labResultRepositry = $labResultRepository;
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        try {
            $user = User::findOrFail($request->user_id);
            $patientProfile = $user->patientProfile;

            if (!$patientProfile) {
                return $this->fail('fail', null, 'Patient profile not found for this user.', 404);
            }

            $labResults = $this->labResultRepositry->getByPatientId($patientProfile->id);

            return $this->success('success', LabResultResource::collection($labResults), "Lab Results Retrieved Successfully!.", 200);
        } catch (\Exception $e) {

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoreLabResultRequest $request)
    {
        try {
            $validatedData = $request->validated();
            $labResult = $this->labResultRepositry->createLabResult($validatedData);

            return $this->success('success', LabResultResource::make($labResult), "Lab Result Created Successfully.", 201);
        } catch (\Exception $e) {

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        try {
            $labResult = $this->labResultRepositry->getLabResultById($id);

            return $this->success('success', LabResultResource::make($labResult), "Lab Result Retrieved Successfully!.", 200);
        } catch (\Exception $e) {

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(StoreLabResultRequest $request, string $id)
    {
        try {
            $validatedData = $request->validated();
            $labResult = $this->labResultRepositry->updateLabResult($validatedData, $id);
            $updatedResult = $this->labResultRepositry->getLabResultById($labResult->id);

            return $this->success('success', LabResultResource::make($updatedResult), "Lab Result Updated Successfully.", 200);
        } catch (\Exception $e) {

            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
