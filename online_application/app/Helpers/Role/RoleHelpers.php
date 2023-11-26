<?php

namespace App\Helpers\Permission;

use DB;
use Schema;
use App\School;
use App\Tenant\Models\Campus;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;


class RoleHelpers
{


    public static function createRole($roleName , $permissions ,School $school)
    {
        $role = Role::create(['name' => $roleName, 'school_id' => $school->id]);

        if (count($request->only(self::PERMISSION_ENTITIES))) {
            foreach ($request->only(self::PERMISSION_ENTITIES) as $permissionBase => $permissions) {
                self::delegatePermissions($permissionBase, $permissions, $role);
            }
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
}
?>
