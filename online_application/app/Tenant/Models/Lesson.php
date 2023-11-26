<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class Lesson extends Model
{
    use ForTenants;
    use Scope;

    public static $modelName = 'lessons';
    protected $fillable = ['date', 'start_time', 'end_time', 'properties'];
    protected $casts = ['properties' => 'array'];

    public $table = 'lessons';

    protected static function boot()
    {
        parent::boot();
        static::addGlobalScope('attendanceCount', function ($builder) {
            $builder->withCount('attendances');
        });
    }

    public function classroomSlot()
    {
        return $this->belongsTo(ClassroomSlot::class);
    }

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function instructor()
    {
        return $this->belongsTo(Instructor::class);
    }

    public function course()
    {
        return $this->belongsTo(Course::class);
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function module()
    {
        return $this->belongsTo(Module::class);
    }

    public function attendances()
    {
        return $this->hasMany(Attendance::class);
    }

    public function attended()
    {
        return $this->attendances->count() > 0 ? 'Yes' : 'No';
    }

    public function lessoneable()
    {
        return $this->morphTo();
    }

    public function groups()
    {
        return $this->morphedByMany(Group::class, 'lessoneable');
    }
}
