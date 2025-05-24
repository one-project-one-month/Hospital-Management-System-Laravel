<?php

namespace App\Http\Controllers\Api;

use App\Models\RecordType;
use App\Traits\HttpResponse;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Repository\RecordTypeRepository;
use App\Http\Resources\RecordTypeResource;
use App\Http\Requests\RecordType\StoreRecordTypeRequest;
use App\Http\Requests\RecordType\UpdateRecordTypeRequest;

class RecordTypeController extends Controller
{
    use HttpResponse;

    protected $recordTypeRepository;

    public function __construct(RecordTypeRepository $recordTypeRepository)
    {
        $this->recordTypeRepository = $recordTypeRepository;
    }

    /**
     * @OA\Get(
     *     path="/api/v1/record-types",
     *     summary="Get all record types",
     *     tags={"Record Types"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Response(response=200, description="List of record types"),
     *     @OA\Response(response=401, description="Unauthorized")
     * )
     */

    public function index(Request $request)
    {
        try {
            $recordTypes = $this->recordTypeRepository->getAllRecordTypes($request);
            return $this->success('success', ['record_types' => RecordTypeResource::collection($recordTypes)], 'Record types retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }


    /**
     * Store a newly created resource in storage.
     */
    /**
     * @OA\Post(
     *     path="/api/v1/record-types",
     *     summary="Create a new record type",
     *     tags={"Record Types"},
     *     security={{"bearerAuth":{}}},
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             required={"name", "description"},
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 enum={"symptom", "diagnosis", "allergy", "family_history", "lifestyle"},
     *                 example="diagnosis"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 example="Describes a medical diagnosis provided by a doctor"
     *             )
     *         )
     *     ),
     *     @OA\Response(response=201, description="Record type created successfully"),
     *     @OA\Response(response=400, description="Bad request")
     * )
     */

    public function store(StoreRecordTypeRequest $request)
    {
        try {
            $recordType = $request->validated();
            $createdRecordType = $this->recordTypeRepository->createRecordType($recordType);
            $createdRecordType = $this->recordTypeRepository->findById($createdRecordType);
            return $this->success('success', ['record_type' => RecordTypeResource::make($createdRecordType)], 'Record type created successfully', 201);
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }

    /**
     * Display the specified resource.
     */

    /**
     * @OA\Get(
     *     path="/api/v1/record-types/{id}",
     *     summary="Get a single record type",
     *     tags={"Record Types"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Record Type ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=200, description="Record type data"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */

    public function show(RecordType $recordType)
    {
        try {
            $showRecordType = $this->recordTypeRepository->findById($recordType);
            return $this->success('success', ['record_type' => RecordTypeResource::make($showRecordType)], 'Record type retrieved successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
    }



    /**
     * Update the specified resource in storage.
     */

    /**
     * @OA\Put(
     *     path="/api/v1/record-types/{id}",
     *     summary="Update a record type",
     *     tags={"Record Types"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Record Type ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\RequestBody(
     *         required=true,
     *         @OA\JsonContent(
     *             @OA\Property(
     *                 property="name",
     *                 type="string",
     *                 enum={"symptom", "diagnosis", "allergy", "family_history", "lifestyle"},
     *                 example="allergy"
     *             ),
     *             @OA\Property(
     *                 property="description",
     *                 type="string",
     *                 example="Updated description"
     *             )
     *         )
     *     ),
     *     @OA\Response(response=200, description="Record type updated"),
     *     @OA\Response(response=400, description="Bad request"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */

    public function update(UpdateRecordTypeRequest $request, RecordType $recordType)
    {
        try {
            $validatedData = $request->validated();
            $recordType = $this->recordTypeRepository->updateRecordType($validatedData, $recordType->id);
            $findRecordType = $this->recordTypeRepository->findById($recordType);
            return $this->success('success', ['record_type' => RecordTypeResource::make($findRecordType)], 'Record type updated successfully', 200);
        } catch (\Exception $e) {
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
        //
    }

    /**
     * Remove the specified resource from storage.
     */

    /**
     * @OA\Delete(
     *     path="/api/v1//record-types/{id}",
     *     summary="Delete a record type",
     *     tags={"Record Types"},
     *     security={{"bearerAuth":{}}},
     *     @OA\Parameter(
     *         name="id",
     *         in="path",
     *         required=true,
     *         description="Record Type ID",
     *         @OA\Schema(type="integer")
     *     ),
     *     @OA\Response(response=204, description="Deleted successfully"),
     *     @OA\Response(response=404, description="Not found")
     * )
     */

    public function destroy(RecordType $recordType)
    {
        try {
            if(!$recordType){
                return  response()->json([
                    'message' => 'RecordType not found.'
                ], 404);

                $recordType = $this->recordTypeRepository->deleteRecordType($recordType->id);
                return $this->success('success',['record_type'=>RecordTypeResource::make($recordType)],'Record type deleted successfully',200);
            }
        } catch (\Exception $e) {
            return $this->fail('fail',null,$e->getMessage(),500);
        }
    }
}
