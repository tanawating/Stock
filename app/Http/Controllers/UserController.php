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

class UserController extends Controller
{
    
    public function __construct(UrlGenerator $url)
    {
        $this->url = $url;
        $this->middleware('auth');
    }

    public function index()
    {
        $role = DB::table('roles')
                ->get();
        return view('user.index',compact('role'));
    }

    public function add_user(Request $request)
    {
        if ( $request->state == 'create' )
        {
            $chk_count = DB::table('users')
                        ->where('email','=',$request->email)
                        ->get();

            if(count($chk_count) > 0)
            {
                return response()->json(['chk_count_success' => true]);
            }
            else
            {
                $users =  DB::table('users')
                            ->insertGetId([
                                    'name'      => $request->name,
                                    'email'     => $request->email,
                                    'password'  => '$2y$10$bAdTLJMhnWs0WbI.D.Rjx.jgBkUJAqmfv3iBGnqKeoMnPKDKNxRT6' //111111
                                ]);

                $role_user = DB::table('role_user')
                            ->insertGetId([
                                    'user_id' => $users,
                                    'role_id' => $request->role
                            ]);

                return response()->json(['success' => true]);
            }

        }
        else if ( $request->state == 'update' )
        {
            $users =  DB::table('users')
                        ->where('id','=',$request->id)
                        ->update([
                                'name'      => $request->name,
                                'email'     => $request->email,
                            ]);

            if($request->role == '')
            {
                $result = $request->id_role;
            }
            else
            {
                $result = $request->role;
            }

            $role_user = DB::table('role_user')
                        ->where('user_id','=',$request->id)
                        ->update([
                                'role_id' => $result
                        ]);

            return response()->json(['edit_success' => true, 'message' => 'update complete']);
        }
    
    }

    public function show($id)
    {
        $users = DB::table('role_user')
                    ->join('roles','roles.id','=','role_user.role_id')
                    ->join('users','users.id','=','role_user.user_id')
                    ->select(
                            'users.name','users.email','roles.display_name as roles_display_name','role_user.user_id as user_id' ,'role_user.role_id as role_id'
                             )
                    ->where('user_id','=',$id)
                    ->first();

        if ( count($users) != 0 )
        {
            return response()->json(['success' => true, 'users' => $users]); 
        }
        else
        {
            return response()->json(['success' => false]); 
        }
    }

    public function querySQL()
    {
        $users = DB::table('role_user')
                    ->join('roles','roles.id','=','role_user.role_id')
                    ->join('users','users.id','=','role_user.user_id')
                    ->select(
                            'users.name','users.email','roles.display_name as roles_display_name','users.id as user_id'
                             );

        return $users;

        Log::info($users);
    }

    public function objectData(Request $request)
    {

        // log::info($request->get('select'));
        $users = $this->querySQL();

        return Datatables::of($users)

        ->addColumn('name', function ($users) {
            return $users->name;
        })
        ->addColumn('email', function ($users) {
            return $users->email;
        })
        ->addColumn('role', function ($users) {

            return $users->roles_display_name;
        })
        ->addColumn('status', function ($users) {

            $button = '<button class="btn btn-success btn-sm"><span class="glyphicon glyphicon-ok"></span> Active</button>';
            return $button;
        })
        ->addColumn('edit', function ($users) {

            $button = '<a href="#" class="btn btn-primary btn-sm" onclick="editData('.$users->user_id.')"><span class="glyphicon glyphicon-edit"></span> Edit</a> <button disabled class="btn btn-danger btn-sm"><span class="glyphicon glyphicon-remove"></span> InActive</button>';
            return $button;
        })

        ->make(true);  

    }

}
