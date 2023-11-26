<?php

namespace App\Tenant\Models;

use App\Tenant\Models\Followup;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Admission extends Model
{
    use ForTenants;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'timezone',
        'available',
        'availability',
        'properties',
    ];

    protected $casts = [
        'properties'        => 'array',
        'availability'      => 'array',
    ];

    public static $modelName = 'admissions';

    public function followups()
    {
        return $this->hasMany(Followup::class);
    }

    public function getAvatarAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5($this->email).'?s=200&r=pg&d=mp';
    }
}
