<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\Scope;
use App\Tenant\Models\Student;
use App\Tenant\Models\Envelope;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Models\Application;
use App\Tenant\Traits\HasCampuses;
use Illuminate\Database\Eloquent\Model;

class Campus extends Model
{
    use ForTenants;
    use HasCampuses;
    use Scope;

    public static $modelName = 'campuses';
    protected $fillable = ['title', 'slug', 'properties', 'details'];
    protected $casts = ['properties' => 'array'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($campus) {
            $relationMethods = ['programs', 'courses', 'classrooms', 'groups', 'instructors'];

            foreach ($relationMethods as $relationMethod) {
                if ($campus->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function classrooms()
    {
        return $this->hasMany(Classroom::class);
    }

    public function groups()
    {
        return $this->hasMany(Classroom::class);
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class);
    }

    public function applications()
    {
        return $this->hasMany(Application::class , 'campus_application');
    }

    public function envelopes()
    {
        return $this->hasMany(Envelope::class , 'campus_envelope');
    }

    public function students()
    {
        return $this->hasMany(Student::class , 'campus_student');
    }
}
