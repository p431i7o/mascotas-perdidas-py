<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use App\Repositories\Permissions;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

// use Spatie\Permission\Contracts\Role;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if($request->wantsJson()){
            $query = User::select('id','name','email','city','address','phone','email_verified_at','active','created_at');
            if (!empty($request->search['value'])) {
                $query->where('id', 'ilike', '%' . $request->search['value'] . '%');
                $query->orWhere('type', 'ilike', '%' . $request->search['value'] . '%');
            }

            $count = $query->count();
            if (isset($request->order)) {
                foreach ($request->order as $order) {
                    $query->orderBy(DB::raw($order['column']+1), $order['dir']);
                }
            } else {
                $query->orderBy('id', 'asc');
            }

            $query->limit($request->length)->offset($request->start);
            $data_result_set = $query->get();
            // dd(User::find(1)->getAllPermissions
            foreach ($data_result_set as $indice => $fila) {
                $data_result_set[$indice]->permissions = User::find($fila->id)->getAllPermissions()
                    ->map(function($item,$key){
                        return [
                            'name'=>$item->name,
                            'id'=>$item->id
                        ];
                    });
            }

            return response()->json([
                'data' => $data_result_set,
                'recordsFiltered' => $count,
                'recordsTotal' => $count,
                'success' => true,
                'params' => $_GET,
                'draw' => (int)$request->draw
            ]);
        }
        return view('users.index')->with('permissions',Permission::select('name','id')->get() );
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function show(User $User)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function edit(User $User)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $User)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $User
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if(!Auth::user()->can(Permissions::MANAGE_USERS)){
            abort(401,'No permitido');
        }

        if($user->id != 1){
            return response()->json(['success'=>$user->delete()]);
        }else{
            return response()->json(['success'=>false]);
        }
    }

    public function updateRole(Request $request, User $user){
        if(!Auth::user()->can(Permissions::MANAGE_USERS)){
            abort(401,'No permitido');
        }

        if($request->action == 'asign'){
            $user->assignRole($request->role);
        }else if($request->action == 'remove'){
            // A admin no se le quita nah
            if($user->id != 1){
                $user->removeRole($request->role);
            }
        }

        return response()->json(['success'=>true],200);
    }
}
