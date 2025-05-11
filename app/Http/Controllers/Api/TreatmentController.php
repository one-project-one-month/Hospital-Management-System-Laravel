<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\Treatment\StoredTreatementRequest;
use App\Http\Requests\Treatment\UpdateTreatmentRequest;
use App\Http\Resources\TreatmentResource;
use App\Models\Appointment;
use App\Models\Treatment;
use App\Models\User;
use App\Repository\TreatmentRepository;
use App\Traits\HttpResponse;
use App\Enums\User as usr;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class TreatmentController extends Controller
{

    use HttpResponse;

    public function __construct(protected TreatmentRepository $treatmentRepository, protected User $user)
    {
        $this->user = Auth::user();
    }

    /**
     * Display a listing of the resource.
     */
    public function index(Treatment $treatment)
    {

        try
            {
               $treatments = $this->treatmentRepository->getAllTreatments($this->user);
                return $this->success('success',['treatments'=>TreatmentResource::collection($treatments)],'Treatments Retrieved Successfully',200);

            }
        catch (\Exception $e)
            {
                return $this->fail('fail',null,$e->getMessage(),500);
            }



    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(StoredTreatementRequest $request, Appointment $appointment)
    {
        try {
            $request->merge([
                'appointment_id'=>$appointment->id
            ]);
            $addTreatment = $request->toArray();
            $createTreatment = $this->treatmentRepository->createTreatment($addTreatment);
            return $this->success('success',['treatment'=>TreatmentResource::make($createTreatment)],'Treatment Created Successfully',201);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */
    public function show($id)
    {
        try {
            $treatment = $this->treatmentRepository->findById($id);
           return $this->success('success',['treatment'=>TreatmentResource::make($treatment)],'Treatment Displayed Successfully',200);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(UpdateTreatmentRequest $request, Appointment $appointment, Treatment $treatment)
    {
        try {
            $request->merge([
                'appointment_id'=>$appointment->id
            ]);
            $validateData = $request->toArray();
            $updateTreatment = $this->treatmentRepository->updateTreatment($validateData,$treatment->id);
            return $this->success('success',['treatment'=>TreatmentResource::make($updateTreatment)],'Treatment Updated Successfully',200);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Appointment $appointment, Treatment $treatment)
    {
        try {
            $deleteTreatment = $this->treatmentRepository->destroyTreatment($treatment->id);
            return $this->success('success',['treatment'=>TreatmentResource::make($deleteTreatment)],'Treatment Deleted Successfully',200);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }
}
