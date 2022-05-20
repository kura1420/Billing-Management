<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class DepartementRequest extends FormRequest
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
            $codeRule = 'nullable|';
        } else {
            $codeRule = 'required|';
        }        

        return [
            //
            'code' => $codeRule . 'string|alpha_num|max:20|unique:departements,code,' . $id,
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'active' => 'required'
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
