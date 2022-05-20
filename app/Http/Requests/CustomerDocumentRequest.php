<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerDocumentRequest extends FormRequest
{
    /**
     * Determine if the user is authorized to make this request.
     *
     * @return bool
     */
    public function authorize()
    {
        return true;
    }

    /**
     * Get the validation rules that apply to the request.
     *
     * @return array
     */
    public function rules()
    {
        $id = request('d_id') ?? NULL;

        if ($id) {
            $fileRules = 'nullable|mimes:jpeg,jpg,png,pdf';
        } else {
            $fileRules = 'required|mimes:jpeg,jpg,png,pdf';
        }
        

        return [
            //
            'd_type' => 'required|string',
            'd_file' => $fileRules,
            'd_identity_number' => 'required|string|max:255|unique:customer_documents,identity_number,' . $id,
            'd_identity_expired' => 'nullable|date',
            'd_customer_contact_id' => 'required|string',
            'customer_data_id' => 'required|string'
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'data' => $validator->errors(),
            'status' => 'NOT'
        ], 422));
    }

    public function attributes()
    {
        return [
            'd_type' => 'type',
            'd_file' => 'file',
            'd_identity_number' => 'identity number',
            'd_identity_expired' => 'identity expired',
            'd_customer_contact_id' => 'customer contact',
        ];
    }
}
