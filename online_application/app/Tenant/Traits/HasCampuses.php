<?php
namespace App\Tenant\Traits;

use Auth;
use App\User;
use App\School;
use App\Helpers\Permission\PermissionHelpers;

trait HasCampuses
{


    public function scopeByCampus($query , $isPermitted = true)
    {
        if(!$isPermitted){
            $campuses= $this->getUser()->campuses;
            $campuses = ($campuses) ? array_column($campuses, 'id') : [];
            if(count($campuses)){
                $query->whereHas('campuses', function ($query) use ($campuses) {
                    $query->whereIn('campuses.id', $campuses);
                });
            }
        }
        return $query;
    }

    /**
     * Get Current Scool
     *
     * @return void
     */
    public function getSchool()
    {
        if (session()->has('tenant')) {
            return School::byUuid(session('tenant'))->first();
        }else{
            return null;
        }
    }


    /**Get School's Campuses List */
    public function getCampusesList()
    {
        $campuses = (isset(session('settings-'.session('tenant'))['campuses'])) ? session('settings-'.session('tenant'))['campuses'] : [];
        $campuses = array_column($campuses, 'title', 'id');
        return $campuses;
    }

    /**
     * get Specific User's campuses or current logged in user
     *
     * @param [type] $user
     * @return void
     */
    public function getUserCampuses($user = null)
    {
        if(!$user){
            $user = $this->getUser();
        }
        return $user->campuses;
    }


    /**
     * Return campuses as list
     *
     * @param [type] $user
     * @return void
     */
    public function getUserCampusesList($user = null)
    {
        if(PermissionHelpers::hasFullAccess($user))
        {
            return $this->getCampusesList();
        }

        $campuses = $this->getUserCampuses($user);
        if(count($campuses)){
            return array_column($campuses , 'title' , 'id' );
        }
        return [];
    }


    public function getUser($id = null)
    {
        if($id){
            return User::find($id);
        }

        return Auth::guard('web')->user();
    }

}

?>
