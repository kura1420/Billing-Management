<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class RadiusApiRequest extends FormRequest
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

        return [
            //
            'name' => 'required|string|max:255',
            'host' => 'required|string|max:100|unique:radius_apis,host,' . $id,
            'active' => 'required',
            'username' => 'required|string|max:100|alpha_num',
            'password' => 'required|string|max:100',
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
