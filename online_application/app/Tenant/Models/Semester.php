<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;

class Semester extends Model
{
    use ForTenants;

    public static $modelName = 'semesters';
    protected $fillable = ['title', 'start_date', 'end_date', 'program_id'];

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function lessons()
    {
        return $this->morphMany(Lesson::class, 'lessoneable');
    }

    public function students()
    {
        $students = new Collection();

        foreach ($this->groups as $group) {
            $students = $students->merge($group->students);
        }

        return $students;
    }

    public function courses()
    {
        return $this->morphedByMany(Course::class, 'semestereable');
    }

    public function programs()
    {
        return $this->morphedByMany(Program::class, 'semestereable');
    }
}
