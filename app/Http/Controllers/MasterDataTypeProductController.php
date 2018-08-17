<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RequestMasterDataTypeProduct;
use DB;
use Redirect;
use Auth;

class MasterDataTypeProductController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $data_type_product = DB::table('data_type_product')
                        ->get();

        return view('master_data.type_product.index',compact('data_type_product'));
    }

    public function store(RequestMasterDataTypeProduct $request)
    {
        if ( $request->state == 'create' )
        {
            DB::table('data_type_product')
                ->insertGetId([
                                'name_type_product'    => $request->name,
                                'created_at'           => date('Y-m-d H:i:s')
                            ]);

            return response()->json(['success' => true, 'message' => 'create complete']);
        }
        else if ( $request->state == 'update' )
        {
            DB::table('data_type_product')
                ->where('id','=',$request->id)
                ->update([
                            'name_type_product'                 =>  $request->name,
                        ]);

            return response()->json(['success' => true, 'message' => 'update complete']);
        }
    }

    public function show($id)
    {
        $data_type_product = DB::table('data_type_product')
                    ->select(
                                'id',
                                'name_type_product'
                            )
                    ->where('id','=',$id)
                    ->first();

        if ( count($data_type_product) != 0 )
        {
            return response()->json(['success' => true, 'data_type_product' => $data_type_product]); 
        }
        else
        {
            return response()->json(['success' => false]); 
        }
    }

    public function destroy($id)
    {
        DB::table('data_type_product')
                    ->where('id','=',$id)
                    ->delete();

        return response()->json(['success' => true]);
    }
}
