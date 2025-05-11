<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\MedicalRecord\StoreMedicalRecordRequest;
use App\Http\Resources\MedicalRecordResource;
use App\Repository\MedicalRecordRepository;
use App\Traits\HttpResponse;
use App\Enums\User as usr;
use App\Models\User;

class MedicalRecordController extends Controller
{
    use HttpResponse;
    public function __construct(protected MedicalRecordRepository $medicalRecordRepository, protected User $user){
        $this->user = \Auth::user();
    }

    public function store(StoreMedicalRecordRequest $request)
    {
        if ($this->user->hasRole(usr\Role::RECEPTIONIST)) {
            try {
                $medicalRecord = $request->validated();
                $record = $this->medicalRecordRepository->store($medicalRecord);

                return $this->success('success',[MedicalRecordResource::make($record->load('medicines'))],'Medical Record added successfully',201 );
            } catch (\Exception $e) {
                return $this->fail('fail', null, $e->getMessage(), 500);
            }
        }

        return $this->fail('fail', null, 'User is not authorized to access this resource', 401);

    }
}
