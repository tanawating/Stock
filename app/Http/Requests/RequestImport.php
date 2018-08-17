<?php

namespace App\Http\Requests;

use App\Http\Requests\Request;
use App\Http\Requests\RequestImport;

class RequestImport extends Request
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
        
                 'get_no'                   =>  'required',
                 'product'                  =>  'required',
                 'get_name_form'            =>  'required',
                 'get_form'                 =>  'required',
                 'treasury'                 =>  'required',
                 'change_serial_numbers'     =>  'required',

        ];
    }
        public function messages()
    {
        return [
                    'get_no.required' => 'กรุณาระบุ เลขที่ใบรับ',
                    'product.required' => 'กรุณาเลือก ผลิตภัณฑ์',
                    'get_name_form.required' => 'กรุณาระบุ ชื่อผู้รับ',
                    'get_form.required' => 'กรุณาระบุข้อมูล',
                    'treasury.required' => 'กรุณาเลือก คลัง',
                    'change_serial_numbers.required' => 'กรุณาเลือกรูปแบบ Serial number',
                ];
    }
}
