<?php

namespace App\Tenant\Models;

use App\Tenant\Models\PaymentGateway;
use App\Tenant\Models\Section;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Field extends Model
{
    use ForTenants;
    use Scope;

    public static $modelName = 'fields';

    protected $fillable = ['label', 'name', 'properties', 'object', 'repeater'];
    protected $casts = ['properties' => 'array', 'data' => 'array'];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function payment()
    {
        return $this->hasOne(PaymentGateway::class);
    }

    public function scopeByName(Builder $builder, $name)
    {
        return $builder->where('name', $name);
    }
}
