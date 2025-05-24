<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorProfileResource extends JsonResource
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
            'name' => $this->user->name,
            'email' => $this->user->email,
            'specialty' => json_decode($this->specialty),
            'availability'=>$this->availability,
            'license_number' => $this->license_number,
            'education' => $this->education,
            'experience_years' => $this->experience_years,
            'biography' => $this->biography,
            'phone' => $this->phone,
            'address' => $this->address,
        ];
    }
}
