<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class BillingTypeRequest extends FormRequest
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
            'code' => $codeRules . '|string|max:20|alpha_num|unique:billing_types,code,' . $id,
            'name' => 'required|string|max:255',
            'desc' => 'nullable|string',
            'notif' => 'required|numeric',
            'suspend' => 'required|numeric',
            'terminated' => 'required|numeric',
            'active' => 'required',
        ];
    }
}
