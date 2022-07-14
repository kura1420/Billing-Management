<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class CustomerDeviceRequest extends FormRequest
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
        $id = request('e_id') ?? NULL;

        if ($id) {
            $fileRules = 'nullable|image';
        } else {
            $fileRules = 'required|image';
        }

        return [
            //
            'e_desc' => 'required|string',
            'e_picture' => $fileRules,
            'e_qty' => 'required|string',
            'e_item_id' => 'required|string',
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
            'e_desc' => 'desc',
            'e_picture' => 'picture',
            'e_qty' => 'qty',
            'e_item_id' => 'item',
        ];
    }
}
