<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\Scope;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\HasCampuses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;


class Course extends Model
{
    use HasCampuses;
    use ForTenants;
    use Scope;

    public static $modelName = 'courses';
    protected $fillable = ['title', 'slug', 'properties', 'status'];
    protected $casts = ['properties' => 'array', 'dates' => 'array'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($course) {
            $relationMethods = ['groups', 'lessons', 'programs'];

            foreach ($relationMethods as $relationMethod) {
                if ($course->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class);
    }

    public function campuses()
    {
        return $this->belongsToMany(Campus::class)->withPivot('campus_id');
    }

    public function scopeInCampus(Builder $builder, $campuses)
    {
        return  $this->belongsToMany(Campus::class)->withPivot('campus_id')->whereIn('campus_id', $campuses);
    }

    public function addons()
    {
        return $this->hasMany(Addon::class, 'object_id', 'id');
    }

    public function dates()
    {
        return $this->hasMany(Date::class, 'object_id', 'id');
    }

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class);
    }

    public function invoiceable()
    {
        return $this->morphToMany(Invoice::class, 'invoiceable')->withTimestamps();
    }




    public function semestereable()
    {
        return $this->morphToMany(Semester::class, 'semestereable')->withTimestamps();
    }

}
