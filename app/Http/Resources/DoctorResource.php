<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class DoctorResource extends JsonResource
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
            'user_id'=>$this->user_id,
            'name'=>$this->user->name,
            'license_number'=>$this->license_number,
            'specialty' => json_decode($this->specialty),
            'education'=>$this->education,
            'availability'=>json_decode($this->availability),
            'experience_years'=>$this->experience_years,
            'biography'=>$this->biography,
            'phone'=>$this->phone,
            'address'=>$this->address,

        ];
    }
}
