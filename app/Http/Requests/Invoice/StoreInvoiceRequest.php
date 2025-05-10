<?php

namespace App\Http\Requests\Invoice;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use App\Traits\HttpResponse;
use Illuminate\Http\Exceptions\HttpResponseException;

class StoreInvoiceRequest extends FormRequest
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
            'appointment_id' => 'required',
            'amount' => 'required|numeric',
            'status' => 'required|in:paid,unpaid',
            'payment_method' => 'required|string|max:30',
            'due_date' => 'required|date'
        ];
    }

    public function failedValidation(Validator $validator){
        throw new HttpResponseException(HttpResponse::fail('fail', $validator->errors(), 'Validation Error', 422));
    }
}
