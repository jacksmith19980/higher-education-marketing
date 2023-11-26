<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\School;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Spatie\Permission\Models\Role;
use Response;

class UserRolesController extends Controller
{
    public function userRolesEdit(School $school, User $user)
    {
        $route = 'user.roles.update';

        $roles = Arr::pluck(Role::where('school_id', $school->id)->orWhere('id', '=', 2)->get(), 'name', 'id');
        $roles_owned = Arr::pluck($user->roles, 'name', 'id');

        $roles += array_diff_assoc($roles_owned, $roles);

        return view(
            'back.user_roles._partials.user_roles',
            compact('user', 'route', 'roles', 'roles_owned')
        );
    }

    public function userRolesUpdate(Request $request, School $school, User $user)
    {
        $user->roles()->detach();
        if (isset($request->roles)) {
            foreach ($request->roles as $role_id) {
                $role = Role::findOrFail($role_id);
                $user->assignRole($role);
            }
        }
        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => ['user_id' => $user->id],
            ]
        );
    }
}
