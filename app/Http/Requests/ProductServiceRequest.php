<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductServiceRequest extends FormRequest
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
        $id = request('id') ?? NULL;

        if ($id) {
            $codeRules = 'nullable|';
        } else {
            $codeRules = 'required|';
        }

        return [
            //
            'code' => $codeRules . '|string|max:20|unique:product_services,code,' . $id,
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'active' => 'required',
            'price' => 'required|numeric',
            'product_type_id' => 'required|string',
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
            'product_type_id' => 'product type'
        ];
    }
}
