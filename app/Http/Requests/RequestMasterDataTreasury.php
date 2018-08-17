<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Http\Requests\RequestMasterDataTreasury;

class RequestMasterDataTreasury extends Request
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
        
                 'name'  =>  'required',

        ];
    }
    public function messages()
    {
        return [
                    'name.required' => 'กรุณาระบุ ชื่อคลัง',
                ];
    }
}
