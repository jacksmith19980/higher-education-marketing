<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Plugin extends Model
{
    use ForTenants;

    protected $fillable = [
        'name',
        'published',
        'type',
        'properties',
    ];

    protected $casts = [
        'properties'        => 'array',
    ];

    public static $modelName = 'plugins';

    public function scopeByType(Builder $builder, $type)
    {
        $builder->where('type', $type);
    }
}
