<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Instructor extends Authenticatable
{
    use ForTenants;
    use Scope;

    public static $modelName = 'instructors';
    protected $fillable = ['first_name', 'last_name', 'email', 'phone', 'password', 'active'];

    protected $hidden = [
        'password', 'remember_token',
    ];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($instructor) {
            $relationMethods = ['groups', 'lessons', 'courses'];

            foreach ($relationMethods as $relationMethod) {
                if ($instructor->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }

    // TODO add Active global return only the actives
    // https://gist.github.com/oliverlundquist/bd1a26d091c17796b557

    public function campuses()
    {
        return $this->belongsToMany(Campus::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function scopeOrderLastName($query)
    {
        return $query->orderBy('last_name');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }

    public function token()
    {
        return $this->hasMany(InstructorToken::class);
    }

    public function getAvatarAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5($this->email).'?s=200&r=pg&d=mp';
    }

    public function scopeByEmail($builder, $email)
    {
        return $builder->where('email', $email);
    }
}
