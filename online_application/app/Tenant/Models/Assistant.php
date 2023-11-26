<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Publishable;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class Assistant extends Model
{
    use ForTenants;
    use Publishable;
    use Scope;

    protected $fillable = ['properties', 'details', 'user_id', 'assistant_builder_id'];
    protected $casts = ['properties' => 'array'];
}
