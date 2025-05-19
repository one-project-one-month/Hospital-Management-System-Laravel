<?php

namespace App\Http\Requests\Medicine;

use App\Traits\HttpResponse;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;


class StoreMedicineRequest extends FormRequest
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
        'name'=>'required|string|max:255|unique:medicines,name',
        'stock'=>'required|numeric|',
        'expired_at'=>'nullable|date'
        ];
    }

    public function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(HttpResponse::fail('fail', $validator->errors(), 'Validation Error', 422));
    }
}
