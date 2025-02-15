<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Repositories\PermissionInterface;
use Illuminate\Http\Request;
use App\Repositories\Permissions;
use Illuminate\Support\Facades\DB;

use Illuminate\Support\Facades\Auth;
use Illuminate\Auth\Events\Registered;
use Spatie\Permission\Models\Permission;

use Illuminate\Support\Facades\Password;
use Illuminate\View\View;
use Illuminate\Http\JsonResponse;


class UserController extends Controller
{
    public function index(Request $request): JsonResponse|View
    {
        if($request->wantsJson())
        {
            $query = User::select('id','name','email','city','address','phone','email_verified_at','active','created_at');
            if (!empty($request->search['value']))
            {
                $query->where('id', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('name', 'like', '%' . $request->search['value'] . '%');
                $query->orWhere('email', 'like', '%' . $request->search['value'] . '%');
            }

            $count = $query->count();
            if (isset($request->order))
            {
                foreach ($request->order as $order)
                {
                    $query->orderBy(DB::raw($order['column']+1), $order['dir']);
                }
            }
            else
            {
                $query->orderBy('id', 'asc');
            }

            $query->limit($request->length)->offset($request->start);
            $data_result_set = $query->get();

            foreach ($data_result_set as $indice => $fila)
            {
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


    public function destroy(User $user): JsonResponse
    {
        if(!Auth::user()->can(PermissionInterface::MANAGE_USERS))
        {
            abort(401,'No permitido');
        }

        if($user->id != 1)
        {
            return response()->json(['success'=>$user->delete()]);
        }
        else
        {
            return response()->json(['success'=>false]);
        }
    }

    public function updateRole(Request $request, User $user): JsonResponse
    {
        if(!Auth::user()->can(PermissionInterface::MANAGE_USERS))
        {
            abort(401,'No permitido');
        }

        if($request->action == 'asign')
        {
            $user->assignRole($request->role);
        }
        else if($request->action == 'remove')
        {
            // A admin no se le quita nah
            if($user->id != 1)
            {
                $user->removeRole($request->role);
            }
        }

        return response()->json(['success'=>true],200);
    }

    public function updatePermission(Request $request, User $user): JsonResponse
    {
        if(!Auth::user()->can(PermissionInterface::MANAGE_USERS))
        {
            abort(401,'No permitido');
        }

        if($request->action == 'asign')
        {
            $user->givePermissionTo($request->permission);
        }
        else if($request->action == 'remove')
        {
            // A admin no se le quita nah
            if($user->id != 1)
            {
                $user->revokePermissionTo($request->permission);
            }
        }

        return response()->json(['success'=>true],200);
    }

    public function sendVerifyMail(Request $request, User $user): JsonResponse
    {
        if(!Auth::user()->can(PermissionInterface::MANAGE_USERS))
        {
            abort(401,'No permitido');
        }
        event(new Registered($user));
        return response()->json(['success'=>true],200);
    }

    public function sendResetPasswordMail(Request $request, User $user): JsonResponse
    {
        if(!Auth::user()->can(PermissionInterface::MANAGE_USERS))
        {
            abort(401,'No permitido');
        }

        Password::sendResetLink(['email'=>$user->email]);
        return response()->json(['success'=>true],200);
    }
}
