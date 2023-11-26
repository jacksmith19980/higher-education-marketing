<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\Scope;
use App\Tenant\Models\Schedule;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Group extends Model
{
    use ForTenants;
    use Scope;

    public static $modelName = 'groups';
    protected $fillable = ['title', 'start_date', 'end_date', 'properties', 'is_active'];
    protected $casts = ['properties' => 'array'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($campus) {
            $relationMethods = ['students', 'lessons'];

            foreach ($relationMethods as $relationMethod) {
                if ($campus->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }

    public function campus()
    {
        return $this->belongsTo(Campus::class);
    }

    public function students()
    {
        return $this->belongsToMany(Student::class);
    }

    public function scopeHasStudents(Builder $builder)
    {
        $builder->wherehas('students');
    }

    public function instructors()
    {
        return $this->belongsToMany(Instructor::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function lessons()
    {
        return $this->morphMany(Lesson::class, 'lessoneable');
    }

    public function lessoneable()
    {
        return $this->morphToMany(Lesson::class, 'lessoneable')->withTimestamps();
    }

    public function semesters()
    {
        return $this->belongsToMany(Semester::class);
    }

    public function schedules()
    {
        return $this->belongsToMany(Schedule::class);
    }
}
