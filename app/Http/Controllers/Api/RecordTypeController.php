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
