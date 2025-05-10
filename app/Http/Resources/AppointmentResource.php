<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
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
            'patient_profile_name'=>$this->patientProfile->user->name,
            'doctor_profile_id'=>$this->doctor_profile_id,
            'doctor_profile_name'=>$this->doctorProfile->user->name,
            'appointment_date'=>$this->appointment_date,
            'appointment_time'=>$this->appointment_time,
            'status'=>$this->status,
            'notes'=>$this->notes,
        ];
    }
}
