<?php

namespace App\Http\Requests;

use Illuminate\Foundation\Http\FormRequest;

class StoreLiquidRequest extends FormRequest
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
            'name'                  => 'required|min:2',
            'base_nic_strength'     => 'required|numeric',
            'target_pg_percentage'  => 'required|numeric',
            'target_vg_percentage'  => 'required|numeric',
            'target_nic_strength'   => 'required|numeric',
            'next_version_id'       => 'nullable',
            'flavours'              => 'required|array',
            'flavours.*.flavour_id' => 'required|exists:flavours,id',
            'flavours.*.percent'    => 'required|numeric',
        ];
    }
}
