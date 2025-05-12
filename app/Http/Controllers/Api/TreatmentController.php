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


/**
 * @OA\Tag(
 *     name="Treatments",
 *     description="API Endpoints for Managing Treatments"
 * )
 */
class TreatmentController extends Controller
{

    use HttpResponse;

    public function __construct(protected TreatmentRepository $treatmentRepository, protected User $user)
    {
        $this->user = Auth::user();
    }

    /**
     * @OA\Get(
     *     path="/api/treatments",
     *     summary="Get treatments for current user",
     *     tags={"Treatments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(
     *         response=200,
     *         description="Treatments Retrieved Successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TreatmentResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error"
     *     )
     * )
     */
    public function index()
    {
        try
            {
               $treatments = $this->treatmentRepository->getAllTreatments($this->user);

                if($this->user->hasRole([usr\Role::PATIENT, usr\Role::DOCTOR])){
                    return $this->success('success',['treatments'=>TreatmentResource::make($treatments)],'Treatments Retrieved Successfully',200);
                }

                return $this->success('success',['treatments'=>TreatmentResource::collection($treatments)],'Treatments Retrieved Successfully',200);

            }
        catch (\Exception $e)
            {
                return $this->fail('fail',null,$e->getMessage(),500);
            }
    }

        /**
     * @OA\Post(
     *     path="/api/appointments/{appointment}/treatments",
     *     summary="Create a treatment for an appointment",
     *     tags={"Treatments"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="appointment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(ref="#/components/schemas/StoredTreatementRequest")
     *     ),
     *     @OA\Response(
     *         response=201,
     *         description="Treatment Created Successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TreatmentResource")
     *     ),
     *     @OA\Response(
     *         response=500,
     *         description="Server Error"
     *     )
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
     *     path="/api/appointments/{appointment}/treatments/{treatment}",
     *     summary="Get a specific treatment by ID",
     *     tags={"Treatments"},
     *     security={{"bearerAuth":{}}},
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
     *     @OA\Response(
     *         response=200,
     *         description="Treatment Displayed Successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TreatmentResource")
     *     )
     * )
     */
    public function show(Appointment $appointment, Treatment $treatment)
    {
        try {
            $showTreatment = $this->treatmentRepository->findById($treatment->id);
           return $this->success('success',['treatment'=>TreatmentResource::make($showTreatment)],'Treatment Displayed Successfully',200);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

        /**
     * @OA\Put(
     *     path="/api/appointments/{appointment}/treatments/{treatment}",
     *     summary="Update a specific treatment",
     *     tags={"Treatments"},
     *     security={{"bearerAuth":{}}},
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
     *         @OA\JsonContent(ref="#/components/schemas/UpdateTreatmentRequest")
     *     ),
     *     @OA\Response(
     *         response=200,
     *         description="Treatment Updated Successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TreatmentResource")
     *     )
     * )
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
     * @OA\Delete(
     *     path="/api/appointments/{appointment}/treatments/{treatment}",
     *     summary="Delete a treatment",
     *     tags={"Treatments"},
     *     security={{"bearerAuth":{}}},
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
     *     @OA\Response(
     *         response=200,
     *         description="Treatment Deleted Successfully",
     *         @OA\JsonContent(ref="#/components/schemas/TreatmentResource")
     *     )
     * )
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
