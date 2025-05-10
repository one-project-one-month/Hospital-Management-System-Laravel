<?php

namespace App\Http\Controllers;

use App\Http\Requests\PatientProfile\UpgradePatientProfileRequest;
use App\Repository\PatientProfileRepository;
use App\Traits\HttpResponse;

class PatientProfileController extends Controller
{

    use HttpResponse;

    protected $patientProfileUpgrade;

    public function __construct(PatientProfileRepository $patientRepository)
    {
        $this->patientProfileUpgrade = $patientRepository;
    }

    public function upgrade(UpgradePatientProfileRequest $request){
        try{

            $validator = $request->validated();
            $validator['user_id'] = auth()->user()->id;
            $patient = $this->patientProfileUpgrade->upgradePatient($validator);
            return $this->success( 'success', ['patient_profile' => $patient], 'patient profile updated successfully' , 201 );

        }catch(\Exception $e){
            
            return $this->fail('fail', null, $e->getMessage(), 500);
        }
        
    }
}
