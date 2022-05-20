<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductTypeRequest extends FormRequest
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
            'code' => $codeRules . '|string|max:20|unique:product_types,code,' . $id,
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'active' => 'required',
        ];
    }

    protected function failedValidation(Validator $validator)
    {
        throw new HttpResponseException(response()->json([
            'data' => $validator->errors(),
            'status' => 'NOT'
        ], 422));
    }
}
