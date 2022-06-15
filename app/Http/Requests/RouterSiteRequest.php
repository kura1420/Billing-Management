<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class RouterSiteRequest extends FormRequest
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
            'site' => 'required|string|max:255',
            'active' => 'required',
            'host' => 'required|string|max:100|unique:router_sites,host,' . $id,
            'port' => 'required|numeric',
            'user' => 'required|string|max:100|alpha_num',
            'password' => 'required|string|max:100',
            'desc' => 'nullable|string',
            'command_trigger_list' => 'required|string',
            'command_trigger_comment' => 'required|string',
            'command_trigger_terminated' => 'required|string',
        ];
    }
}
