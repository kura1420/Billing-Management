<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerCandidateRequest extends FormRequest
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
            'fullname' => 'required|string|max:255',
            'email' => 'required|string|max:100|email|unique:customer_candidates,email',
            'handphone' => 'required|numeric|unique:customer_candidates,handphone',
            'file' => 'required|mimes:jpeg,jpg,png,pdf',
            'address' => 'required|string',
            'longitude' => 'nullable|string',
            'latitude' => 'nullable|string',
            'from' => 'required|string',
            'signature' => 'required|string',
            'provinsi_id' => 'required|string',
            'city_id' => 'required|string',
            'customer_segment_id' => 'required|string',
            'product_service_id' => 'required|string',
            'area_product_promo_id' => 'nullable|string',
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
            'fullname' => 'nama',
            'file' => 'identitas',
            'address' => 'alamat',
            'signature' => 'tanda tangan pelanggan',
            'provinsi_id' => 'provinsi',
            'city_id' => 'kota',
            'product_service_id' => 'layanan',
            'area_product_promo_id' => 'promo',
        ];
    }
}
