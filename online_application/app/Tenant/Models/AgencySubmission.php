<?php

namespace App\Tenant\Models;

use App\Tenant\Models\Agency;
use App\Tenant\Models\Application;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class AgencySubmission extends Model
{
    use ForTenants;

    protected $fillable = ['data', 'properties', 'status'];
    protected $casts = [
        'data' => 'array',
        'properties' => 'array',
    ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }
}
