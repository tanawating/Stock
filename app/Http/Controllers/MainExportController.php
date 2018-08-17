<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\UrlGenerator;
use App\Http\Requests\RequestExport;
use DB;
use Datatables;
use Log;
use DateTime;
use Excel;
use Auth;

class MainExportController extends Controller
{

    public function __construct(UrlGenerator $url)
    {   
        $this->url = $url;
        $this->middleware('auth');
    }

    public function index()
    {
        return view('main.export.index');
    }

    public function querySQL()
    {
        $stock = DB::table('main_stock')
                    ->join('data_treasury','data_treasury.id','=','main_stock.treasury_id')
                    ->join('data_type_product','data_type_product.id','=','main_stock.product_id')
                    ->select(
                            'main_stock.id as stock_id',
                            'main_stock.created_at as time_stock',
                            'main_stock.get_no',
                            'data_type_product.name_type_product',
                            'data_treasury.name_treasury',
                            'main_stock.qty_start',
                            'main_stock.qty_total'
                             )
                    ->orderBy('main_stock.id','=','desc');

                    // Log::info($stock);

        return $stock;
    }

    public function objectData(Request $request)
    {


        // log::info($request->get('select'));
        $stock = $this->querySQL();

        return Datatables::of($stock)

        ->addColumn('time_stock', function ($stock) {
            return $stock->time_stock;
        })
        ->addColumn('get_no', function ($stock) {
            return $stock->get_no;
        })
        ->addColumn('name_type_product', function ($stock) {
            return $stock->name_type_product;
        })
        ->addColumn('name_treasury', function ($stock) {
            return $stock->name_treasury;
        })
        ->addColumn('qty_start', function ($stock) {
            return $stock->qty_start;
        })
        ->addColumn('qty_total', function ($stock) {
            return $stock->qty_total;
        })
        ->addColumn('detail', function ($stock) {

            if($stock->qty_total == '0')
            {
                return '<a href="#" disabled class="btn btn-default btn-sm"><span class="glyphicon glyphicon-share"></span> ตัดสต็อก</a>
                        <a href="#"  onclick="chk_log('.$stock->stock_id.')" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-align-justify"></span> ประวัติการตัดสต็อก</a>
                       ';
            }
            else
            {
                return '<a href="#" onclick="editData(this,'.$stock->stock_id.')" class="btn btn-primary btn-sm"><span class="glyphicon glyphicon-share"></span> ตัดสต็อก</a>
                        <a href="#"  onclick="chk_log('.$stock->stock_id.')" class="btn btn-success btn-sm"><span class="glyphicon glyphicon-align-justify"></span> ประวัติการตัดสต็อก</a>
                       ';
            }
        })

        ->make(true);  

    }

    public function store(RequestExport $request)
    {
        // Log::info('Auto = '.$request->qty_device_cut_auto);
        // Log::info('Manual = '.$request->qty_device_cut_manual);
        // Log::info('Import File = '.$request->qty_device_cut_import_file);

        if ($request->qty_device_cut_manual != '0') 
        {
            $qty_start = $request->device_total;
            $qty_cut = $request->qty_device_cut_manual;
            $qty_result = $qty_start - $qty_cut;
        }
        if ($request->qty_device_cut_auto != '0') 
        {
            $qty_start = $request->device_total;
            $qty_cut = $request->qty_device_cut_auto;
            $qty_result = $qty_start - $qty_cut;
        }
        if ($request->qty_device_cut_import_file != '0') 
        {
            $qty_start = $request->device_total;
            $qty_cut = $request->qty_device_cut_import_file;
            $qty_result = $qty_start - $qty_cut;
        }  

        if ( $request->state == 'update' )
        {

            if(empty($request->qty_device_cut_auto) && empty($request->qty_device_cut_manual))
            {

                if ($request->hasFile('excel_file')) 
                {

                    $file = $request->file('excel_file');
                    $qty_import_file = $request->qty_device_cut_import_file;

                    $rows = Excel::selectSheetsByIndex(0)->load($file, function ($reader) use($request){})->get();

                    // $rows = $reader->toArray();
                    $qty_rows = count($rows);
                    $qty = $request->qty_device_cut_import_file;

                    foreach ($rows as $key => $value) 
                    {
                         $chk_sn_step1 = DB::table('main_device')
                            ->where('stock_id','=',$request->id)
                            ->where('is_status','!=',false)
                            ->count();

                         $chk_sn_step2 = DB::table('main_device')
                            ->where('serial_number','!=', $value['serial_number'])
                            ->where('stock_id','=',$request->id)
                            ->where('is_status','!=',false)
                            ->count();
                        if($qty != $qty_rows)
                        {
                            // Log::info('จำนวนไม่เท่ากัน');
                            return response()->json(['chk_qty' => true]);
                        }
                        else if($chk_sn_step1 == $chk_sn_step2)
                        {
                            // Log::info('Serial number ไม่ตรงกัน หรือ ไม่มีในระบบ');
                            return response()->json(['chk_sn' => true]);
                        }
                        else
                        {

                            $serial_number = isset($value['serial_number']) ? (string)$value['serial_number'] : '' ;

                            $main_device = DB::table('main_device')
                                            ->where('serial_number','=',$serial_number)
                                            ->update
                                                ([
                                                    'serial_number'     =>  $serial_number,
                                                    'is_status'         =>  false,
                                                ]);
                        }
                    }
                }

            }
            else
            {
                $input = $request->input;
                foreach ($input as $key => $value) 
                {

                    $chk_sn_step1 = DB::table('main_device')
                                    ->where('stock_id','=',$request->id)
                                    ->where('is_status','!=',false)
                                    ->count();

                    $chk_sn_step2 = DB::table('main_device')
                                    ->where('serial_number','!=', $value['serial_number'])
                                    ->where('stock_id','=',$request->id)
                                    ->where('is_status','!=',false)
                                    ->count();

                    // log::info($chk_sn_step1.' '.$chk_sn_step2);

                    if($chk_sn_step1 == $chk_sn_step2)
                    {
                        return response()->json(['chk_sn' => false]);
                    }
                    else
                    {
                        DB::table('main_device')
                            ->where('serial_number','=', $value['serial_number'])
                            ->where('stock_id','=',$request->id)
                            ->where('is_status','!=',false)
                            ->update([
                                        'send_form'     =>  $request->sent_form,
                                        'send_detail'   =>  $request->send_detail,
                                        'is_status'     =>  false
                                    ]);
                    }

                }
            }

            DB::table('main_stock')
                ->where('id','=',$request->id)
                ->update([
                            'qty_total'  =>  $qty_result,
                        ]);

            DB::table('main_stock_cut')
                ->insertGetId([
                                'stock_id'      => $request->id,
                                'sent_form'     => $request->sent_form,
                                'sent_qty'      => $qty_cut,
                                'created_at'    => date('Y-m-d H:i:s')
                             ]);
                
            return response()->json(['success' => true]);
        }
        
    }

    public function store_import_excel(Request $request)
    {
        if ($request->hasFile('excel_file')) 
        {
            $file = $request->file('excel_file');


            Excel::selectSheetsByIndex(0)->load($file, function ($reader) {

                $rows = $reader->toArray();

                foreach ($rows as $key => $value) 
                {
                    $name_type_product      = isset($value['name_type_product']) ? (string)$value['name_type_product'] : '' ;

                    $data_type_product  = DB::table('data_type_product')
                                        ->where('name_type_product','=',$name_type_product)
                                        ->first();
                    if (count($data_type_product) > 0) {
                        $import = DB::table('data_type_product')
                                    ->where('name_type_product','=',$name_type_product)
                                    ->update
                                        ([
                                            'name_type_product'     =>  $name_type_product,
                                        ]);
                    }
                    else{
                        $import = DB::table('data_type_product')
                                        ->insert
                                            ([
                                                'name_type_product'     =>  $name_type_product,
                                            ]);
                    }
                }
            });
            return response()->json(['success' => true]);
        }
        else
        {
            return response()->json(['success' => false]);
        }
    }

    public function show($id)
    {
           $main_stock = DB::table('main_stock')
                    ->select('main_stock.*','main_stock.id as main_stock_id','data_treasury.*','data_type_product.*')
                    ->join('data_treasury','data_treasury.id','=','main_stock.treasury_id')
                    ->join('data_type_product','data_type_product.id','=','main_stock.product_id')
                    ->where('main_stock.id','=',$id)
                    ->first();
            $main_device = DB::table('main_device')
                    ->where('is_status','=',true)
                    ->where('stock_id','=',$id)
                    ->count();

        if ( count($main_stock) != 0 )
        {
            return response()->json(['success' => true, 'main_stock' => $main_stock ,'main_device' => $main_device]); 
        }
        else
        {
            return response()->json(['success' => false]); 
        }
    }

    public function get_device(Request $request,$id)
    {
            $get_qty_device = $request->num;

            $main_device = DB::table('main_device')
                    ->where('is_status','=',true)
                    ->where('stock_id','=',$id)
                    ->take($get_qty_device)
                    ->get();

        if ( count($main_device) != 0 )
        {
            return response()->json(['success' => true, 'main_device' => $main_device]); 
        }
        else
        {
            return response()->json(['success' => false]); 
        }
    }

    public function show_log($id)
    {
           $main_stock_log = DB::table('main_stock_cut')
                    ->select('main_stock_cut.*')
                    ->where('main_stock_cut.stock_id','=',$id)
                    ->get();

        if ( count($main_stock_log) != 0 )
        {
            return response()->json(['success' => true, 'main_stock_log' => $main_stock_log]); 
        }
        else
        {
            return response()->json(['success' => false]); 
        }
    }

}
