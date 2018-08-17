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

class RoleController extends Controller
{
    
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
        $this->middleware('auth');
    }

    public function index()
    {
        $roles = DB::table('roles')
                    ->get();

        $permission_role = DB::table('permission_role')
                                ->select('permission_role.*','permissions.display_name')
                                ->join('permissions','permission_role.permission_name','=','permissions.name')
                                ->get();

        $pages = DB::table('permissions')
                        ->select('page')
                        ->groupBy('page')
                        ->get();

        return view('role.index',compact('roles','permission_role','pages'));
    }

    public function show($id)
    {
        $roles = DB::table('roles')
                    ->select('roles.*')
                    ->where('id','=',$id)
                    ->first();

        if ( count($roles) != 0 )
        {
            return response()->json(['success' => true, 'roles' => $roles]); 
        }
        else
        {
            return response()->json(['success' => false]); 
        }
    }

    public function add_role(Request $request)
    {
        if ( $request->state == 'create' )
        {
            $role_name = strtolower(preg_replace('/\s+/', '_', $request->display_name));
            $role_name = strtolower(preg_replace('/\//', '', $role_name));

            $role = DB::table('roles')
                        ->insertGetId([
                                    'name'          =>  $role_name,
                                    'display_name'  =>  $request->display_name,
                                    'created_at'    =>  date('Y-m-d H:i:s'),
                                    'updated_at'    =>  date('Y-m-d H:i:s')
                                ]);

            $permissions = DB::table('permissions')
                                ->get();

            foreach ($permissions as $key => $permission) 
            {
                DB::table('permission_role')
                    ->insert([
                                // 'permission_id'     =>  $value->id,
                                'role_id'           =>  $role,
                                'permission_name'   =>  $permission->name,
                                'page'              =>  $permission->page,
                                'is_checked'        =>  false
                            ]);
            }

            return response()->json(['success' => true, 'message' => 'create complete']);
        }
        else if ( $request->state == 'update' )
        {
            $role_name = strtolower(preg_replace('/\s+/', '_', $request->display_name));
            $role_name = strtolower(preg_replace('/\//', '', $role_name));

            $users =  DB::table('roles')
                        ->where('id','=',$request->id)
                        ->update([
                                'name'              =>  $role_name,
                                'display_name'      => $request->display_name,
                            ]);

            return response()->json(['edit_success' => true, 'message' => 'update complete']);
        }
    }

    public function delete_role($id)
    {
        // Log::info($id);
        DB::table('roles')
                ->where('id','=',$id)
                ->delete();

        return response()->json(['success' => true]);
    }

    public function querySQL()
    {
        $roles = DB::table('roles')
                    ->select(
                            'roles.id',
                            'roles.display_name'
                             );

                    // Log::info($stock);

        return $roles;
    }

    public function objectData(Request $request)
    {

        // log::info($request->get('select'));
        $roles = $this->querySQL();

        return Datatables::of($roles)

        ->addColumn('time_stock', function ($roles) {
            return $roles->display_name;
        })
        ->addColumn('edit', function ($roles) {

            $button = '<a href="#" onclick="editData('.$roles->id.')" class="btn btn-success btn-sm"><i class="fa fa-pencil-square-o" aria-hidden="true"></i> Edit</a> <a href="#" onclick="deleteData('.$roles->id.')" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>';
            return $button;
        })

        ->make(true);  
    }

    public function role_permission()
    {
        $roles = DB::table('roles')
                    ->get();

        $permission_role = DB::table('permission_role')
                                ->select('permission_role.*','permissions.display_name')
                                ->join('permissions','permission_role.permission_name','=','permissions.name')
                                ->get();

        $pages = DB::table('permissions')
                        ->select('page')
                        ->groupBy('page')
                        ->get();

        return view('role_permission.index',compact('roles','permission_role','pages'));
    }
    
    public function role_permission_store(Request $request)
    {
        $permission_role = $request->permission_role['id'];

        foreach ($permission_role as $key => $value) 
        {
            $data = explode(",", $value);
            $id = $data[0];
            $state = $data[1];

            $permission_role = DB::table('permission_role')->where('id','=',$id)->first();

            $permission = DB::table('permissions')
                                ->where('name','=',$permission_role->permission_name)
                                ->first();

            DB::table('permission_role')
                    ->where('id','=',$id)
                    ->update([
                                'permission_id' =>  $state == 'on' ? $permission->id : null,
                                'is_checked'    =>  $state == 'on' ? true : false
                            ]);
        }

        return redirect('main/role')->with('success', 'Update role permission complete');
    }

}
