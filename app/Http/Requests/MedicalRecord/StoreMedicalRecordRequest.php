<?php

namespace App\Http\Requests\MedicalRecord;

use App\Traits\HttpResponse;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreMedicalRecordRequest extends FormRequest
{

    use HttpResponse;
    /**
     * Determine if the user is authorized to make this request.
     */
    public function authorize(): bool
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array<string, \Illuminate\Contracts\Validation\ValidationRule|array<mixed>|string>
     */
    public function rules(): array
    {
        return [
            'appointment_id' => 'required|exists:appointments,id',
            'record_type_id' => 'required|exists:record_types,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'recorded_at' => 'required|date',
            'medicines' => 'required|array',
            'medicines.*.medicine_id' => 'required|exists:medicines,id',
            'medicines.*.quantity' => 'required|integer|min:1',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException($this->fail('fail', $validator->errors(), 'Validation Error', 422));
    }
}
