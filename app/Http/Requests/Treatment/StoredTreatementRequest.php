<?php

namespace App\Http\Requests\Treatment;

use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;
use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;

class StoredTreatementRequest extends FormRequest
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
            'appointment_id' => 'exists:appointments,id',
            'title'         => 'required | max:225 | string',
            'description'   => 'required | string',
            'start_date'    => 'required | date',
            'end_date'      => 'nullable | date'
        ];


    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(HttpResponse::fail('fail', $validator->errors(), 'Validation Error', 422));
    }
}
