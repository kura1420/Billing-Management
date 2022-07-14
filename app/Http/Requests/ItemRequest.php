<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class ItemRequest extends FormRequest
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
            'code' => $codeRule . 'string|alpha_num|max:36|unique:items,code,' . $id,
            'name' => 'required|string|max:255',
            'serial_numbers' => 'nullable|string|max:255|unique:items,code,' . $id,
            'spec' => 'required|string',
            'desc' => 'nullable|string',
            'year' => 'required|numeric',
            'picture' => $codeRule . '|image',
            'price' => 'required|numeric',
            'brand' => 'required|string|max:255',
            'partner_id' => 'required|string',
            'unit_id' => 'required|string',
        ];
    }

    public function attributes()
    {
        return [
            'partner_id' => 'partner',
            'unit_id' => 'unit',
        ];
    }
}
