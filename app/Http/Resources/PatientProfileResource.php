<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class PatientProfileResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @return array<string, mixed>
     */
    public function toArray(Request $request): array
    {
        return [
            'id' => $this->id,
            'user_id' => $this->user_id,
            'gender' => $this->gender,
            'date_of_birth' => $this->date_of_birth,
            'phone' => $this->phone,
            'address' => $this->address,
            'relation' => $this->relation,
            'blood_type' => $this->blood_type
        ];
    }
}
