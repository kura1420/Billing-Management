<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerCandidateStoreRequest extends FormRequest
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
        return [
            //
            'c_fullname' => 'required|string|max:255',
            'c_email' => 'required|string|max:100|email|unique:customer_candidates,email',
            'c_handphone' => 'required|numeric|unique:customer_candidates,handphone',
            'c_file' => 'required|mimes:jpeg,jpg,png,pdf',
            'c_file_type' => 'required|string',
            'c_file_number' => 'required|string',
            'c_address' => 'required|string',
            'c_longitude' => 'nullable|string',
            'c_latitude' => 'nullable|string',
            'c_provinsi_id' => 'required|string',
            'c_city_id' => 'required|string',
            'c_customer_segment_id' => 'required|string',
            'c_product_service_id' => 'required|string',
            'c_area_product_promo_id' => 'nullable|string',
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
            'c_fullname' => 'nama',
            'c_file' => 'identitas',
            'c_file_type' => 'file type',
            'c_file_number' => 'file number',
            'c_address' => 'alamat',
            'c_provinsi_id' => 'provinsi',
            'c_city_id' => 'kota',
            'c_product_service_id' => 'layanan',
            'c_area_product_promo_id' => 'promo',
        ];
    }
}
