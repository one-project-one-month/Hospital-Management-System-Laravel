<?php

namespace App\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;

class MedicalRecordResource extends JsonResource
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
            'title' => $this->title,
            'record_type_id' => $this->record_type_id,
            'appointment_id' => $this->appointment_id,
            'description' => $this->description,
            'recorded_at' => $this->recorded_at,
            'medicine_price' => $this->medicine_price,
            'appointment' => AppointmentResource::make($this->appointment),
            'record_type' => RecordTypeResource::make($this->record_type),
            'medicines' => MedicineResource::collection($this->medicines)
        ];
    }
}
