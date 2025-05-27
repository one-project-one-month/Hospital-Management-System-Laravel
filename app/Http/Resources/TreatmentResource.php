<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use App\Http\Resources\AppointmentResource;
use Illuminate\Http\Resources\Json\JsonResource;

class TreatmentResource extends JsonResource
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
            'appointment_id' => $this->appointment_id,
            'title' => $this->title,
            'description' => $this->description,
            'start_date' => $this->start_date,
            'end_date'=>$this->end_date,
            'appointment' => AppointmentResource::make($this->appointment)
        ];
    }
}
