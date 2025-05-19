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
     * @OA\Get(
     *     path="/api/v1/appointments/{appointment}/treatments",
     *     summary="List treatments for an appointment",
     *     tags={"Treatments"},
     *     @OA\Parameter(
     *         name="appointment",
     *         in="path",
     *         required=true,
     *         description="Appointment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful"),
     *     @OA\Response(response=404, description="Appointment not found")
     * )
     */
    public function index(Appointment $appointment)
    {
        try
            {
               $treatments = $this->treatmentRepository->getAllTreatments();
                return $this->success('success',['treatments'=>TreatmentResource::collection($treatments)],'Treatments Retrieved Successfully',200);

            }
        catch (\Exception $e)
            {
                return $this->fail('fail',null,$e->getMessage(),500);
            }
    }

     /**
     * @OA\Post(
     *     path="/api/v1/appointments/{appointment}/treatments",
     *     summary="Create a new treatment",
     *     tags={"Treatments"},
     *     @OA\Parameter(
     *         name="appointment",
     *         in="path",
     *         required=true,
     *         description="Appointment ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"title", "description", "start_date"},
     *             @OA\Property(property="title", type="string", maxLength=225, example="Back pain therapy"),
     *             @OA\Property(property="description", type="string", example="This treatment includes massage and exercise."),
     *             @OA\Property(property="start_date", type="string", format="date", example="2025-05-13"),
     *             @OA\Property(property="end_date", type="string", format="date", nullable=true, example="2025-05-20")
     *         )
     *     ),
     *     @OA\Response(response=201, description="Created"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
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
     * @OA\Get(
     *     path="/api/v1/appointments/{appointment}/treatments/{treatment}",
     *     summary="Show treatment detail",
     *     tags={"Treatments"},
     *     @OA\Parameter(
     *         name="appointment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="treatment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Successful"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function show(Appointment $appointment, Treatment $treatment)
    {
        try {
            $showTreatment = $this->treatmentRepository->findById($appointment,$treatment);
           return $this->success('success',['treatment'=>TreatmentResource::make($showTreatment)],'Treatment Displayed Successfully',200);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    /**
     * @OA\Put(
     *     path="/api/v1/appointments/{appointment}/treatments/{treatment}",
     *     summary="Update a treatment",
     *     tags={"Treatments"},
     *     @OA\Parameter(
     *         name="appointment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Parameter(
     *         name="treatment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"end_date", },
     *
     *             @OA\Property(property="end_date", type="string", format="date"),
     *
     *         )
     *     ),
     *     @OA\Response(response=200, description="Updated"),
     *     @OA\Response(response=422, description="Validation Error")
     * )
     */
    public function update(UpdateTreatmentRequest $request, Appointment $appointment, Treatment $treatment)
    {
        try {
            $validateData = $request->toArray();
            $treatment = $this->treatmentRepository->updateTreatment($validateData,$treatment);
            $updateTreatment = $this->treatmentRepository->findById($appointment,$treatment);
            return $this->success('success',['treatment'=>TreatmentResource::make($updateTreatment)],'Treatment Updated Successfully',200);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }
}
