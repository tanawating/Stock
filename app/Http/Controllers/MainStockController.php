<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;

use Illuminate\Routing\UrlGenerator;
use DB;
use Datatables;
use Log;
use DateTime;
use Auth;

class MainStockController extends Controller
{

    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
        $this->middleware('auth');
    }
    
    public function index()
    {
        $stocks = DB::table('main_stock')
                    ->join('data_treasury','data_treasury.id','=','main_stock.treasury_id')
                    ->join('data_type_product','data_type_product.id','=','main_stock.product_id')
                    ->get();

        return view('main.stock.index',compact('stocks'));
    }

    public function querySQL()
    {
        $stock = DB::table('main_stock')
                    ->join('data_treasury','data_treasury.id','=','main_stock.treasury_id')
                    ->join('data_type_product','data_type_product.id','=','main_stock.product_id')
                    ->select(
                            'main_stock.created_at as time_stock',
                            'main_stock.qty_total',
                            'data_treasury.name_treasury',
                            'data_type_product.name_type_product'
                             )
                    ->orderBy('main_stock.id','=','desc');

                    Log::info($stock);

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
        ->addColumn('type_product', function ($stock) {
            return $stock->name_type_product;
        })
        ->addColumn('treasury', function ($stock) {
            return $stock->name_treasury;
        })
        ->addColumn('total', function ($stock) {
            return $stock->qty_total;
        })

        ->make(true);  

    }

}
