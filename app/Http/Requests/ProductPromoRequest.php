<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ProductPromoRequest extends FormRequest
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
            $imageRules = 'nullable|';
        } else {
            $codeRules = 'required|';
            $imageRules = 'required|';
        }

        return [
            //
            'code' => $codeRules . '|string|max:20|unique:product_services,code,' . $id,
            'name' => 'required|string|max:255',
            'desc' => 'required|string',
            'active' => 'required',
            'start' => 'required|date',
            'end' => 'required|date|after:start',
            'image' => $imageRules . 'image',

            'type' => 'required|string|max:20',
            'discount' => 'required|numeric',
            'until_payment' => 'required|numeric',
            // 'product_promo_id' => 'required|string',
            'product_service_id' => 'nullable|string',
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
            'product_service_id' => 'product service'
        ];
    }
}
