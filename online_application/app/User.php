<?php

namespace App;

use DB;
use Str;
use App\School;
use App\Tenant\Traits\HasCampuses;
use App\Tenant\Traits\HasMessages;
use Spatie\Permission\Traits\HasRoles;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class User extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use HasRoles;
    use HasCampuses;
    use HasMessages;


    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'email', 'password', 'is_active', 'roles', 'activation_token' , 'phone' , 'position' , 'team_id' , 'settings', 'signature'];

    protected $guard_name = 'web';

    public static $modelName = 'users';

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password', 'remember_token',
    ];

    public static function getModelName()
    {
        return self::$modelName;
    }

    public function schools()
    {
        return $this->belongsToMany(School::class);
    }

    public function scopeByActivationMethods(Builder $builder, $email, $token)
    {
        $builder->where([
            'email'             => $email,
            'activation_token'  => $token,
            'is_active'         => false,
        ]);
    }

    public function getAvatarAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5($this->email).'?s=200&r=pg&d=mp';
    }

    public function scopeBySchool(Builder $builder)
    {
        $builder->where('school_id',session('school')->id);
    }

    /**
     * Get Comma separated list of user's roles
     *
     * @return String
     */
    public function getRolesListAttribute()
    {
        $roles = $this->roles()->where('school_id',session('school')->id)->pluck('name')->toArray();
        return (count($roles)) ? implode(', ', $roles) : 'N/A';
    }




    public function team()
    {
        return $this->belongsTo(Team::class);
    }
    // Get User's Campuses
    public function getCampusesAttribute()
    {
        $list = [];
        $school = School::byUuid(session('tenant'))->first();
        $settings = session('settings-'.session('tenant'));
        $campusIds = DB::table('user_campus')
                    ->where('user_id', $this->id)
                    ->where('school_id', $school->id)
                    ->pluck('campus_id')->toArray();
        if(isset($settings['campuses'])){
            foreach($campusIds as $campusId){
                $list[$campusId] = $settings['campuses'][$campusId];
            }
        }
        return $list;
    }
    // Get User's Language
    public function getLanguageAttribute()
    {
        $settings = $this->settings;
        if($settings && !is_array($settings)){
            $settings = json_decode($settings , true);
            if(isset($settings['language']) && !is_null($settings['language'])){
                return $settings['language'];
            }
        }
        return null;
    }

    /**
     * Save Campuses Relation
     *
     * @param [type] $campuses
     * @return void
     */
    public function saveCampuses($campuses)
    {
        $school = School::byUuid(session('tenant'))->first();
        foreach ($campuses as $campus){
            DB::insert('insert into user_campus (campus_id, user_id,  school_id) values (?, ?,?)', [$campus,  $this->id , $school->id ]);
        }
    }
    /**
     * Remove Campus or all campuses
     *
     * @param [type] $campuses
     * @return void
     */
    public function detachCampuses($campuses = null)
    {

        $school = School::byUuid(session('tenant'))->first();
        $query = "delete from user_campus where school_id = $school->id and user_id = $this->id";
        if($campuses){
            $campuses = implode(', ', $campuses);
            $query .=" and campus_id in ($campuses)";
        }
        return DB::delete($query);
    }
    public function syncRoles(...$roles)
    {
        //merge roles from other schools to the selected roles so we don't lose the other school's roles
        $roles = $this->roles()->where('school_id','!=',session('school')->id)->get()->merge($roles);
        return $this->assignRole($roles);
    }


    public function getFirstnameAttribute()
    {
        return explode(" " , $this->name)[0];
    }

    public function getLastnameAttribute()
    {
        $temp = explode(" " , $this->name);
        if(count($temp) > 1){
            unset($temp[0]);
            return implode(" " , $temp);
        }else{
            return $temp[0];
        }
    }

    public function getIsHemAttribute()
    {
        return Str::of($this->email)->contains('@higher-education-marketing.com');
    }



}
