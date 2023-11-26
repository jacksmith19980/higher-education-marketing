<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class Classroom extends Model
{
    use ForTenants;
    use Scope;

    public static $modelName = 'classrooms';
    protected $fillable = ['title', 'location', 'capacity', 'properties'];
    protected $casts = ['properties' => 'array'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($campus) {
            $relationMethods = ['lessons'];

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

    public function lessons()
    {
        return $this->hasMany(Lesson::class);
    }

    public function classroomSlots()
    {
        /*return $this->hasMany(ClassroomSlot::class)->orderBy('start_time');*/
        return $this->hasMany(ClassroomSlot::class)->orderBy('day');
    }

    public function scopeOrderTitle($query)
    {
        return $query->orderBy('title');
    }

    public function scopeOrderCampus($query)
    {
        return $query->orderBy('campus_id');
    }

    public function scopeActive($query)
    {
        return $query->where('is_active', 1);
    }
}
