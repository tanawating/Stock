<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use Illuminate\Routing\UrlGenerator;
use DB;
use Datatables;
use Log;
use DateTime;
use Auth;

class MainSearchController extends Controller
{

    public function __construct(urlGenerator $url)
    {
        $this->url = $url;
        $this->middleware('auth');
    }
    
    public function index()
    {
        return view('main.search.index');
    }

    public function detail($id)
    {
            $detail = DB::table('main_device')
                    ->join('main_stock','main_stock.id','=','main_device.stock_id')
                    ->select('main_device.*','main_stock.get_no as main_stock_get_no')
                    ->where('main_device.stock_id','=',$id)
                    ->orderBy('main_device.id','asc')
                    ->get();

            $get_no = DB::table('main_device')
                    ->join('main_stock','main_stock.id','=','main_device.stock_id')
                    ->where('main_device.stock_id','=',$id)
                    ->first();

        if ( count($detail) != 0 )
        {
            return response()->json(['success' => true, 'detail' => $detail ,'get_no' => $get_no]); 
        }
        else
        {
            return response()->json(['success' => false]); 
        }
    }
    
    public function querySQL()
    {
        $search = DB::table('main_device')
                    ->join('main_stock','main_stock.id','=','main_device.stock_id')
                    ->join('data_type_product','data_type_product.id','=','main_stock.product_id')
                    ->groupBy('get_no')
                    ->orderBy('main_device.id','=','desc')
                    ->select(
                                'main_stock.id as id_stock',
                                'main_device.created_at',
                                'main_stock.get_no',
                                'data_type_product.name_type_product'
                            );

                    // Log::info($stock);

        return $search;
    }
    public function objectData(Request $request)
    {


        // log::info($request->get('select'));
        $search = $this->querySQL();

        return Datatables::of($search)
        ->filter(function ($query) use ($request) {
            if ($request->has('get_no')) {
                $query->where( 'main_stock.get_no', '=', $request->get('get_no'));
            }
        })

        ->addColumn('created_at', function ($search) 
        {
            return $search->created_at;
        })
        ->addColumn('get_no', function ($search) 
        {
            return $search->get_no;
        })
        ->addColumn('name_type_product', function ($search) 
        {
            return $search->name_type_product;
        })
        ->addColumn('detail', function ($search) 
        {
            return '<a href="#" class="btn btn-success btn-sm" onclick="detail('.$search->id_stock.')""><span class="glyphicon glyphicon-th-list"></span> Detail</a>';
        })

        ->make(true);  

    }

}
