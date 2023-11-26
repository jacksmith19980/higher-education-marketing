<?php

namespace App\Http\Controllers\Tenant;

use App\User;
use Response;
use App\School;
use App\Tenant\Models\Role;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Helpers\Permission\PermissionHelpers;

class RoleController extends Controller
{

    protected const PERMISSION_ENTITIES = ['campus', 'course' , 'program' , 'user' , 'agent' , 'application' , 'submission' , 'settings' , 'contact' ,'field', 'stage' , 'envelope'] ;

    protected const PERMISSION_BASE = "settings";

    public function index()
    {
        // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE,
        ]);
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }

        $params = [
            'modelName'   => 'roles',
        ];

        $school = School::byUuid(session('tenant'))->first();

        $roles = Role::where('school_id', $school->id)->get();

        return view('back.roles.index', compact('roles', 'params' , 'permissions'));
    }

    public function create()
    {
        $permissions =  PermissionHelpers::areGranted([
            'create|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $school = School::ByUuid(session('tenant'))->first();
        $users = $school->users->pluck('name', 'id');

        return view('back.roles.create', compact('users' , 'permissions'));
    }

    public function store(Request $request)
    {
        $permissions =  PermissionHelpers::areGranted([
            'create|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $request->validate([
            'name' => 'required',
        ]);

        try {
            $school = School::byUuid(session('tenant'))->first();

            $data = [
                    'name'      => $request->name,
                    'school_id' => $school->id,
                    'full_access' => ($request->full_access == 'Yes') ? true : false
            ];

            $role = Role::create($data);

            if (count($request->only(self::PERMISSION_ENTITIES))) {
                foreach ($request->only(self::PERMISSION_ENTITIES) as $permissionBase => $permissions) {
                    $this->delegatePermissions($permissionBase, $permissions, $role);
                }
            }

            if (isset($request->users) && count($request->users) > 0) {
                foreach ($request->users as $user_id) {
                    $user = User::findOrFail($user_id);
                    $user->assignRole($role);
                }
            }

            $message = ['success' => "Role {$role->name } created successfully!"];
        } catch (\Exception $e) {
            $message = ['error' => $e->getMessage()];
        }

        return redirect(route('roles.index'))
            ->with($message);
    }

    public function show($id)
    {
        //
    }

    public function edit(Role $role)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $role)) {
            return PermissionHelpers::accessDenied();
        }

        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
        ]);


        $school = School::ByUuid(session('tenant'))->first();
        $users = $school->users->pluck('name', 'id');

        return view('back.roles.edit', compact('users', 'role'));
    }

    public function update(Request $request, Role $role)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $role)) {
            return PermissionHelpers::accessDenied();
        }

        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
        ]);

        $request->validate([
            'name' => 'required',
        ]);

        try {
            $role->update([
                'name' => $request->name,
                'full_access' => ($request->full_access == 'Yes') ? true : false
            ]);

            // Degelate Permission to the Role
            if(count($request->only(self::PERMISSION_ENTITIES))){
                $allPermissions = $role->getPermissionNames()->toArray();

                foreach($request->only(self::PERMISSION_ENTITIES) as $permissionBase => $permissions){

                    // Revoke All Old Permissions
                    $this->revokeAllPermissions($role , $allPermissions ,$permissionBase);
                    $this->delegatePermissions( $permissionBase , $permissions , $role  );
                }
            }
            if (isset($request->users) && count($request->users) > 0) {
                foreach ($request->users as $user_id) {
                    $user = User::findOrFail($user_id);
                    $user->assignRole($role);
                }
            }

            $message = ['success' => "Role {$role->name } updated successfully!"];
        } catch (\Exception $e) {
            $message = ['error' => $e->getMessage()];
        }

        return redirect(route('roles.index'))
            ->with($message);
    }

    public function destroy(Role $role)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', $role)) {
            return PermissionHelpers::accessDenied();
        }
        $permissions =  PermissionHelpers::areGranted([
            'delete|' . self::PERMISSION_BASE,
        ]);

        if ($response = $role->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $role->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }


    protected function delegatePermissions($permissionBase, $permissions  ,$role): void
    {
        foreach($permissions as $permission => $value){
            if($value){
                if($permission != 'full'){
                    $role->givePermissionTo( $permission . '|' . $permissionBase);
                }
            }
        }
    }


    /**
     * Revoke all permissions from a role
     *
     * @param [type] $role
     * @param [type] $permissionBase
     * @return void
     */
    protected function revokeAllPermissions($role , $allPermissions ,$permissionBase = null)
    {
        if(!$permissionBase){
            return false;
        }
        foreach ($allPermissions as $permission) {
            $tmp = explode('|', $permission);
            if($permissionBase == $tmp[1]){
                $role->revokePermissionTo($permission);
            }
        }

        return true;
    }
}
