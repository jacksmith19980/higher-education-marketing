<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class ClassroomSlot extends Model
{
    use ForTenants;
    use Scope;

    public static $modelName = 'classroomSlots';
    protected $fillable = ['schedule_id', 'start_time', 'end_time', 'day', 'properties'];

    public function classroom()
    {
        return $this->belongsTo(Classroom::class);
    }

    public function lesson()
    {
        return $this->hasOne(Lesson::class);
    }

    public function schedule()
    {
        return $this->hasOne(Schedule::class);
    }
}
