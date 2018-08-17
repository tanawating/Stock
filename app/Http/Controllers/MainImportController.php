<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\UrlGenerator;
use App\Http\Requests\RequestImport;
use DB;
use Datatables;
use Log;
use DateTime;
use Excel;
use Auth;

class MainImportController extends Controller
{

    public function __construct(UrlGenerator $url)
    {
        $this->middleware('auth');
        $this->url = $url;
    }

    public function index()
    {   
        $data_type_product = DB::table('data_type_product')
                                ->get();

        $data_treasury = DB::table('data_treasury')
                                ->get();

        return view('main.import.index',compact('data_type_product','data_treasury'));
    }

    public function store(RequestImport $request)
    {

        if ($request->hasFile('excel_file')) 
        {

            $file = $request->file('excel_file');

            $excels = Excel::selectSheetsByIndex(0)->load($file, function ($reader){})->get(['serial_number']);   

            //validate Column serial_number = none
            if(count($excels) == 0)
            {
                return response()->json(['excel_none' => true]);
            }
            else if(count($excels) != 0)
            {
                foreach ($excels as $key => $excel) 
                {
                    $serial_number = isset($excel['serial_number']) ? (string)$excel['serial_number'] : '' ;

                    //validate Column serial_number = name false/null
                    if(empty($serial_number))
                    {
                        return response()->json(['excel_null' => true]);
                    }
                }
            }

            $add_stock = DB::table('main_stock')
                        ->insertGetId([
                                        'get_time'      => date('Y-m-d H:i:s'),
                                        'get_no'        => $request->get_no,
                                        'get_form'      => $request->get_form,
                                        'get_name_form' => $request->get_name_form,
                                        'product_id'    => $request->product,
                                        'treasury_id'   => $request->treasury,
                                        'qty_start'     => count($excels),
                                        'qty_total'     => count($excels),
                                        'detail'        => $request->detail,
                                        'created_at'    => date('Y-m-d H:i:s')
                                     ]);    

            foreach ($excels as $key => $excel) 
            {
                $serial_number = isset($excel['serial_number']) ? (string)$excel['serial_number'] : '' ;
                $add_device = DB::table('main_device')
                                 ->insertGetId([
                                                'stock_id'      => $add_stock,
                                                'serial_number' => $serial_number,
                                                'is_status'     => true,
                                                'created_at'    => date('Y-m-d H:i:s')
                                              ]);
            }
            return response()->json(['success_excel' => true]);

        }
        else
        {
            $add_stock = DB::table('main_stock')
                        ->insertGetId([
                                        'get_time'      => date('Y-m-d H:i:s'),
                                        'get_no'        => $request->get_no,
                                        'get_form'      => $request->get_form,
                                        'get_name_form' => $request->get_name_form,
                                        'product_id'    => $request->product,
                                        'treasury_id'   => $request->treasury,
                                        'qty_start'     => $request->qty,
                                        'qty_total'     => $request->qty,
                                        'detail'        => $request->detail,
                                        'created_at'    => date('Y-m-d H:i:s')
                                     ]);

            $input = $request->input;
            foreach ($input as $key => $value) 
            {
                $add_device = DB::table('main_device')
                                 ->insertGetId([
                                                'stock_id'      => $add_stock,
                                                'serial_number' => $value['serial_number'],
                                                'is_status'     => true,
                                                'created_at'    => date('Y-m-d H:i:s')
                                              ]);
            }

            return response()->json(['success' => true]);
        }
        
    }

}
