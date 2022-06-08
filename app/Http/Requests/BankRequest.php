<?php

namespace App\Http\Requests;

use App\Models\Bank;
use Illuminate\Foundation\Http\FormRequest;
use Illuminate\Contracts\Validation\Validator;
use Illuminate\Http\Exceptions\HttpResponseException;

class BankRequest extends FormRequest
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
            'code' => $codeRules . '|string|max:20|alpha_num|unique:banks,code,' . $id,
            'name' => 'required|string|max:255',
            'active' => [
                'required',
                function ($attr, $val, $fail) use ($id) {
                    if ($val == "true") {
                        $bank = Bank::where('active', 1)->first();

                        if ($bank->id !== (int) $id && $bank->active == 1) {
                            $fail("Hanya di perbolehkan 1 bank saja yang aktif.");
                        }
                    }
                }
            ],
            'responsible_name' => 'required|string|max:255',
            'rekening' => $codeRules . '|string|max:100|alpha_num|unique:banks,rekening,' . $id,
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
