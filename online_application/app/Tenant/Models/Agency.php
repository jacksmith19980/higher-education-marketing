<?php

namespace App\Tenant\Models;

use App\Tenant\Models\AgencySubmission;
use App\Tenant\Models\Agent;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Agency extends Model
{
    use ForTenants;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'address',
        'postal_code',
        'country',
        'city',
        'description',
        'approved',
    ];

    public static $modelName = 'agencies';

    public function agents()
    {
        return $this->hasMany(Agent::class);
    }

    public function students()
    {
        return $this->hasManyThrough(Student::class, Agent::class);
    }

    public function submissions()
    {
        return $this->students()->get();
    }

    public function scopeByNameOrEmail(Builder $builder, $attribute, $value)
    {
        $builder->where($attribute, $value);
    }

    public function agencySubmissions()
    {
        return $this->hasMany(AgencySubmission::class);
    }
}
