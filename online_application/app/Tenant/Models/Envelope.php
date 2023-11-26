<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\Scope;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Contract;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\HasCampuses;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Envelope extends Model
{
    use ForTenants;
    use HasCampuses;
    use Scope;

    protected $fillable = ['title', 'service', 'properties'];

    protected $casts = [
            'properties' => 'array',
    ];

    public static $modelName = 'envelope';

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function campuses()
    {
        return $this->belongsToMany(Campus::class);
    }


}
