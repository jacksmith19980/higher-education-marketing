<?php

namespace App\Helpers\Permission;

use DB;
use Schema;
use App\School;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Permission;
use Illuminate\Support\Facades\Auth;



class PermissionHelpers
{
    public const REDIRECTIO_ON_FAIL = 'dashboard';

    /**
     * Redirect to Access Denied Page
     *
     * @return void
     */
    public static function accessDenied()
    {
        return redirect(route(self::REDIRECTIO_ON_FAIL));

    }

    public static function verifyPermission($permission)
    {
        return count(self::getUser()->getPermissionsViaRoles()->where('name', $permission)) > 0;
    }

    public static function allPermissions($user = null)
    {
        // Get only Permission for the role that belongs to current school
        $list = [];
        $roles = session('school')->roles->pluck('id')->toArray();
        $user = self::getUser($user);
        $permissions = [];
        if($user){
            $permissions = $user->getAllPermissions()->toArray();
        }
        if(count($roles)){

            foreach ($permissions as $permission) {
                if(in_array($permission['pivot']['role_id'] , $roles)){
                    $list[] = $permission;
                }
            }
            return collect($list);
        }

        return collect($permissions);
    }

    /**
     * Check if list of permissions are granted
     *
     * @param array $permissions
     * @return boolean
     */
    public static function areGranted($permissions = [])
    {


        $hasFullAccess = self::hasFullAccess();
        $granted = [];
        $allPermissions = self::allPermissions()->pluck('name')->toArray();
        foreach ($permissions as $permission){
            $granted[$permission] = (in_array($permission, $allPermissions) || $hasFullAccess )? true : false;
        }
        return $granted;
    }

    protected static function getSchool()
    {
        return (session('school')) ? session('school') : null ;
    }

    /**
     * check if specific user has full access
     *
     * @param [type] $user
     * @return boolean
     */
    public static function hasFullAccess($user = null)
    {
        if(!$user){
            $user = self::getUser();
        }
        if(!$user){
            return false;
        }
        $role = $user->roles()->where('school_id', self::getSchool()->id)->where('full_access', true)->first();

        return $role ? true : false;
    }

    /**
     * Get a specific user or return current one
     *
     * @param [type] $id
     * @return void
     */
    protected static function getUser($user = null){
        if(!$user){
            return Auth::guard('web')->user();
        }
        if(is_numeric($user)){
            return User::find($user);
        }
        return $user;
    }

    public static function checkActionPermission($modelName, $action, $model = null)
    {
        $permissions = self::areGranted([
            $action . '|' . $modelName,
            'campuses' . ucwords($action) . '|' . $modelName
        ]);

        // return TRUE or FALSE if action is permitted
        switch ($action) {
            // @TODO check View Cross Campuses
            case 'view':
                $canViewCrossCampuses = self::checkCrossCampusesPermission($permissions[ 'campuses' . ucwords($action) . '|' . $modelName ] , $model);
                return ($permissions[$action . '|' . $modelName] && $canViewCrossCampuses) ? true : false;
            break;

            case 'create':
                return self::areGranted([$action . '|' . $modelName])[$action . '|' . $modelName];
            break;

            case 'edit':

                $canEditCrossCampuses = self::checkCrossCampusesPermission($permissions[ 'campuses' . ucwords($action) . '|' . $modelName ] , $model);
                return ($permissions[$action . '|' . $modelName] && $canEditCrossCampuses) ? true : false;
            break;


            case 'delete':
                $canDeleteCrossCampuses = self::checkCrossCampusesPermission($permissions[ 'campuses' . ucwords($action) . '|' . $modelName ], $model);
                return ($permissions[$action . '|' . $modelName] && $canDeleteCrossCampuses) ? true : false;
            break;

        }
    }

    /**check if current user can perform actions on a model */
    protected static function checkCrossCampusesPermission($isPermitted = true , $model = null)
    {
        // return ture if user can perform the action cross campuses or if Not checking a specfiec model entity
        if($isPermitted || !$model){
            return true;
        }

        // If user is not permitted to perform the action cross campus we chceck if the model's campus belongs to this user
        $campuses  = (count(self::getUser()->campuses)) ? array_column( self::getUser()->campuses , 'id' ) : [];

        // Check if model has Campuses
        if($modelCampuses = $model->campuses){
            if(is_array($modelCampuses)){
                $modelCampuses = array_column($modelCampuses, 'id');
            }else{
                $modelCampuses = $modelCampuses->pluck('id')->toArray();
            }
            return count(array_intersect($campuses, $modelCampuses));
        }else{
            // Model has no Campuses, return true
            return true;
        }
    }



}
