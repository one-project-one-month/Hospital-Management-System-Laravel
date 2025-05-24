<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\InvoiceResource;
use App\Http\Resources\TreatmentResource;
use App\Http\Resources\DoctorProfileResource;
use App\Http\Resources\MedicalRecordResource;
use Illuminate\Http\Resources\Json\JsonResource;

class AppointmentResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id'=>$this->id,
            'patient_profile_id'=>$this->patient_profile_id,
            'patient_profile_name'=>$this->patientProfile->name,
            'appointment_date'=>$this->appointment_date,
            'appointment_time'=>$this->appointment_time,
            'status'=>$this->status,
            'notes'=>$this->notes,
            'doctor'=>DoctorProfileResource::make($this->doctorProfile),

        ];
    }
}
