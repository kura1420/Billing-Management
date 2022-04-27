<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class CustomerRequest extends FormRequest
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
            // 'code' => $codeRules . '|string|max:20|unique:customer_data,code,' . $id,
            'active' => 'required|string',
            // 'member_at' => 'nullable|date',
            // 'suspend_at' => 'nullable|date',
            // 'terminate_at' => 'nullable|date',
            // 'dismantle_at' => 'nullable|date',
            'customer_type_id' => 'required|string',
            'customer_segment_id' => 'required|string',
            'area_id' => 'required|string',
            'provinsi_id' => 'required|string',
            'city_id' => 'required|string',
            'area_product_id' => 'required|string',
            'area_product_customer_id' => 'required|string',
            'product_type_id' => 'required|string',
            'product_service_id' => 'required|string',

            'name' => 'nullable|string|max:255',
            'gender' => 'nullable|string',
            'email' => 'required|string|email|max:100|unique:customer_profiles,email,' . $id,
            'telp' => 'nullable|numeric',
            'handphone' => 'required|numeric',
            'fax' => 'nullable|string|max:50',
            'address' => 'required|string',
            'picture' => 'nullable|image',
            'birthdate' => 'nullable|date',
            'marital_status' => 'nullable|string',
            'work_type' => 'nullable|string',
            'child' => 'nullable|numeric',
        ];
    }

    public function attributes()
    {
        return [
            'customer_type_id' => 'customer type',
            'customer_segment_id' => 'customer segment',
            'area_id' => 'area',
            'provinsi_id' => 'provinsi',
            'city_id' => 'city',
            'area_product_id' => 'area product',
            'area_product_customer_id' => 'area product customer',
            'product_type_id' => 'product type',
            'product_service_id' => 'product service',
        ];
    }
}
