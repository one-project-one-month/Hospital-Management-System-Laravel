<?php

namespace App\Http\Requests\LabResult;

use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreLabResultRequest extends FormRequest
{
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
            'appointment_id' => 'required|integer|exists:appointments,id',
            'test_name' => 'required|string|max:255',
            'result_summary' => 'nullable|string',
            'detailed_result' => 'nullable|string',
            'performed_at' => 'required|date',
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(HttpResponse::fail('fail', $validator->errors(), 'Validation Error', 422));
    }
}
