<?php

namespace App\Http\Controllers;

use App\Http\Requests;
use Illuminate\Http\Request;
use DB;
use Auth;

class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $stock = DB::table('main_stock')
                    ->join('data_treasury','data_treasury.id','=','main_stock.treasury_id')
                    ->join('data_type_product','data_type_product.id','=','main_stock.product_id')
                    ->get();

        return view('main.stock.index',compact('stock'));

        // test commit
    }

}
