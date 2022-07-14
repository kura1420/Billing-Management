<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class PartnerDocumentRequest extends FormRequest
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
        $id = request('d_id') ?? NULL;

        if ($id) {
            $fileRules = 'nullable|mimes:jpeg,jpg,png,pdf';
        } else {
            $fileRules = 'required|mimes:jpeg,jpg,png,pdf';
        }

        return [
            //
            'd_name' => 'required|string|max:255',
            'd_file' => $fileRules,
            'd_desc' => 'required|string',
            'd_start' => 'required|date',
            'd_end' => 'required|date',
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
            'd_name' => 'name',
            'd_file' => 'file',
            'd_desc' => 'desc',
            'd_start' => 'start',
            'd_end' => 'end',
        ];
    }
}
