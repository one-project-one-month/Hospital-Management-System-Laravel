<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalRecord\StoreMedicalRecordRequest;
use App\Http\Resources\MedicalRecordResource;
use App\Repository\MedicalRecordRepository;
use App\Traits\HttpResponse;
use App\Enums\User as usr;
use App\Models\MedicalRecord;
use App\Models\User;

class MedicalRecordController extends Controller
{
    use HttpResponse;
    public function __construct(protected MedicalRecordRepository $medicalRecordRepository, protected User $user){
        $this->user = \Auth::user();
    }

    /**
     * @OA\Post(
     *     path="/api/v1/medical-record",
     *     summary="Store a new medical record",
     *     tags={"Medical Records"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"appointment_id", "record_type_id", "title", "description"},
     *             @OA\Property(property="appointment_id", type="integer", example=1),
     *             @OA\Property(property="record_type_id", type="string", format="uuid", example="a12b3c4d-5e6f-7a8b-9c0d-ef1234567890"),
     *             @OA\Property(property="title", type="string", example="General Checkup"),
     *             @OA\Property(property="description", type="string", example="The patient reported headaches and fatigue."),
     *             @OA\Property(
     *                 property="medicines",
     *                 type="array",
     *                 @OA\Items(
     *                     @OA\Property(property="id", type="integer", example=5),
     *                     @OA\Property(property="quantity", type="integer", example=2)
     *                 )
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Medical record created successfully"),
     *     @OA\Response(response=400, description="Bad Request"),
     *     @OA\Response(response=401, description="Unauthenticated")
     * )
     */

    public function store(StoreMedicalRecordRequest $request)
    {
        if ($this->user->hasRole(usr\Role::RECEPTIONIST)) {
            try {
                $medicalRecord = $request->validated();
                $record = $this->medicalRecordRepository->store($medicalRecord);
                $createdMedicalRecord=MedicalRecord::where('id',$record->id)->first();
                return $this->success('success',[MedicalRecordResource::make($createdMedicalRecord->load('medicines'))],'Medical Record added successfully',201 );
            } catch (\Exception $e) {
                return $this->fail('fail', null, $e->getMessage(), 500);
            }
        }

        return $this->fail('fail', null, 'User is not authorized to access this resource', 401);

    }
}
