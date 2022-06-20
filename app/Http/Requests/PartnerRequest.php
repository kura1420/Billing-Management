<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PartnerRequest extends FormRequest
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
            'code' => 'required|string|max:50|alpha_num|unique:partners,code,' . $id,
            'name' => 'required|string|max:255',
            'alias' => 'nullable|string|max:100|unique:partners,alias,' . $id,
            'type' => 'required|string|max:100',
            'telp' => 'nullable|numeric|unique:partners,telp,' . $id,
            'email' => 'required|string|max:100|email|unique:partners,email,' . $id,
            'fax' => 'nullable|string|max:100',
            'handphone' => 'nullable|numeric|unique:partners,handphone,' . $id,
            'address' => 'required|string',
            'logo' => 'nullable|image',
            'active' => 'required',
            'join' => 'nullable|date',
            'leave' => 'nullable|date',
            'brand' => 'nullable|string|max:255',
            'user_id_reff' => 'nullable|string',
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
            'user_profile_id_reff' => 'employee referensi',
        ];
    }
}
