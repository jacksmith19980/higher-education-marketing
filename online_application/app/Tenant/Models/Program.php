<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\Scope;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\HasCampuses;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use ForTenants;
    use Scope;
    use HasCampuses;

    public static $modelName = 'programs';
    protected $fillable = ['title', 'slug', 'uid', 'program_type', 'start_dates', 'properties', 'details'];
    protected $casts = ['properties' => 'array', 'dates' => 'array'];

    protected static function boot()
    {
        parent::boot();

        static::deleting(function ($program) {
            $relationMethods = ['groups'];

            foreach ($relationMethods as $relationMethod) {
                if ($program->$relationMethod()->count() > 0) {
                    return false;
                }
            }
        });
    }

    public function campuses()
    {
        return $this->belongsToMany(Campus::class);
    }

    public function courses()
    {
        return $this->belongsToMany(Course::class);
    }

    public function groups()
    {
        return $this->hasMany(Group::class);
    }

    public function modules()
    {
        return $this->hasMany(Module::class);
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
