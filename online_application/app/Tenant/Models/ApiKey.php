<?php

namespace App\Tenant\Models;

use App\School;
use App\Tenant\Traits\Scope;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class ApiKey extends Model
{
    use HasFactory, Scope;

    protected $fillable = [
        'ap_key',
        'is_active'
    ];

    public function school()
    {
        return $this->belongsTo(School::class);
    }

}
