<?php

namespace App\Tenant\Models;

use App\Tenant\Models\Admission;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Followup extends Model
{
    use ForTenants;

    protected $fillable = [
        'name',
        'email',
        'phone',
        'type',
        'status',
        'properties',
    ];

    protected $casts = [
        'properties'        => 'array',
    ];

    public function admission()
    {
        return $this->belongsTo(Admission::class);
    }

    public static $modelName = 'followups';
}
