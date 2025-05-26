<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalRecord\StoreMedicalRecordRequest;
use App\Http\Resources\MedicalRecordResource;
use App\Repository\MedicalRecordRepository;
use App\Traits\HttpResponse;
use App\Enums\User as usr;
use App\Enums\User\Role;
use App\Http\Requests\MedicalRecord\UpdateMedicalRecord;
use App\Http\Resources\MedicineResource;
use App\Models\Appointment;
use App\Models\MedicalRecord;
use App\Models\User;
use GuzzleHttp\Psr7\Request;
use Illuminate\Support\Facades\Redis;

class MedicalRecordController extends Controller
{
    use HttpResponse;
    public function __construct(protected MedicalRecordRepository $medicalRecordRepository, protected User $user){
        $this->user = \Auth::user();
    }



     /**
     * @OA\Get(
     *     path="/api/v1/medical-records",
     *     tags={"MedicalRecord"},
     *     summary="Get all medical records",
     *     @OA\Response(response=200, description="List of medical records")
     * )
     */
     public function index()
     {
        if($this->user->hasRole(usr\Role::RECEPTIONIST)){
            try{
                $medicalRecords = $this->medicalRecordRepository->getAllMedicalRecords();
                return $this->success('success', [ 'medicalRecords' => MedicalRecordResource::collection($medicalRecords)], 'medical records reterived successfully', 200 );
            }catch(\Exception $error){
                return $this->fail('fail', null, $error->getMessage(), 500);
            }
        }

        return $this->fail("fail", null, 'user is not authorized to enter this resource', 403);
     }

     /**
     * @OA\Get(
     *     path="/api/v1/appointments/{appointment}/medical-record",
     *     tags={"MedicalRecord"},
     *     summary="Get a single medical record by appointment ID",
     *     @OA\Parameter(
     *         name="appointment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Medical record found"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */

     public function show(Appointment $appointment)
     {
        if($this->user->hasRole(usr\Role::RECEPTIONIST)){
            try{
                $medicalRecord = $this->medicalRecordRepository->getMedicalRecord($appointment);
                if( ! $medicalRecord){
                    return $this->fail('fail', null, 'medical record not fount', 404 );
                }
            return $this->success('success', [ 'medicalRecord'  =>  new MedicalRecordResource($medicalRecord)], 'all medical records reterived successfully', 200 );
            }catch(\Exception $error){
                return $this->fail("fail", null, $error->getMessage(), 500);
            }
        }
     }

      /**
     * @OA\Delete(
     *     path="/api/appointments/{appointment}/medical-record",
     *     tags={"MedicalRecord"},
     *     summary="Delete a medical record",
     *     @OA\Parameter(
     *         name="appointment",
     *         in="path",
     *         required=true,
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Deleted"),
     *     @OA\Response(response=404, description="Not Found")
     * )
     */
    public function destroy(Appointment $appointment)
    {
        if($this->user->hasRole(usr\Role::RECEPTIONIST)){
            $medicalRecord = $this->medicalRecordRepository->deleteMedicalRecord($appointment);
            return $this->success("success", null , 'medical record deleted successfully', 200 );
        }

        return $this->fail('fail', null, 'user is not authorized to enter this resource', 403 );
    }

    /**
     * @OA\Post(
     *     path="/api/v1/appointment{appointment}/medical-record",
     *     summary="Store a new medical record",
     *     tags={"MedicalRecord"},
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
     *                     @OA\Property(property="medicine_id", type="integer", example=5),
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


    public function store(StoreMedicalRecordRequest $request,Appointment $appointment)
    {
        if ($this->user->hasRole(usr\Role::RECEPTIONIST)) {
            try {
                $validated_data = $request->validated();
                $validated_data['appointment_id'] = $appointment['id'];
                $record = $this->medicalRecordRepository->store($validated_data);
                if(! $record){
                    return $this->fail('fail', null, 'medical record already exists for this appointment', 400);
                }

                $createdMedicalRecord=MedicalRecord::where('id',$record->id)->first();
                return $this->success('success',[MedicalRecordResource::make($createdMedicalRecord->load('medicines'))],'Medical Record added successfully',201 );
            } catch (\Exception $e) {
                return $this->fail('fail', null, $e->getMessage(), 500);
            }
        }

        return $this->fail('fail', null, 'User is not authorized to access this resource', 403);

    }

     /**
     * @OA\Put(
     *     path="/api/v1/appointment{appointment}/medical-record",
     *     summary="Store a new medical record",
     *     tags={"MedicalRecord"},
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
     *                     @OA\Property(property="medicine_id", type="integer", example=5),
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

    public function update(UpdateMedicalRecord $request, Appointment $appointment)
    {
        if($this->user->hasRole(usr\Role::RECEPTIONIST)){
            $validated_data = $request->validated();
            $validated_data['appointment_id'] = $appointment['id'];
            $medical_record = $this->medicalRecordRepository->updateMedicalRecord($validated_data, $appointment);
            return $this->success('success', ['medicalRecord' => new MedicalRecordResource($medical_record) ], 'medical record updated successfully', 200 );
        }

        return $this->fail('fail', null, 'user is not authorized to enter this resource', 403 );
    }
}
