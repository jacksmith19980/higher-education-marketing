<?php

namespace App\Tenant\Models;

use App\Tenant\Models\Group;
use App\Tenant\Traits\Scope;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Schedule extends Model
{
    use ForTenants;
    use Scope;

    public static $modelName = 'schedules';
    protected $fillable = ['label', 'start_time', 'end_time'];

    public function classroomSlots()
    {
        return $this->hasMany(ClassroomSlot::class);
    }

    public function groupes()
    {
        return $this->hasMany(Group::class);
    }
}
