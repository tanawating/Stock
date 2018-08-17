<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

use App\Http\Requests;
use App\Http\Requests\RequestMasterDataTreasury;
use DB;
use Redirect;
use Auth;

class MasterDataTreasuryController extends Controller
{

    public function __construct()
    {
        $this->middleware('auth');
    }
    
    public function index()
    {
        $data_treasury = DB::table('data_treasury')
                        ->get();
        return view('master_data.treasury.index',compact('data_treasury'));
    }

    public function store(RequestMasterDataTreasury $request)
    {
        if ( $request->state == 'create' )
        {
            DB::table('data_treasury')
                ->insertGetId([
                                'name_treasury'    => $request->name,
                                'created_at'           => date('Y-m-d H:i:s')
                            ]);

            return response()->json(['success' => true, 'message' => 'create complete']);
        }
        else if ( $request->state == 'update' )
        {
            DB::table('data_treasury')
                ->where('id','=',$request->id)
                ->update([
                            'name_treasury'        => $request->name,
                        ]);

            return response()->json(['success' => true, 'message' => 'update complete']);
        }
    }

    public function show($id)
    {
        $data_treasury = DB::table('data_treasury')
                    ->select(
                                'id',
                                'name_treasury'
                            )
                    ->where('id','=',$id)
                    ->first();

        if ( count($data_treasury) != 0 )
        {
            return response()->json(['success' => true, 'data_treasury' => $data_treasury]); 
        }
        else
        {
            return response()->json(['success' => false]); 
        }
    }

    public function destroy($id)
    {
        DB::table('data_treasury')
                    ->where('id','=',$id)
                    ->delete();

        return response()->json(['success' => true]);
    }
}
