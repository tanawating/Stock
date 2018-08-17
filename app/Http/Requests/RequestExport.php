<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Http\Requests\RequestExport;

class RequestExport extends Request
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
        
                     'sent_form'        =>  'required',
                     'select_cuts'      =>  'required',
               ];
    }
        public function messages()
    {
        return [
                    'sent_form.required'    => 'กรุณาระบุข้อมูล',
                    'select_cuts.required'  => 'กรุณาระบุข้อมูล',
               ];
    }
}
