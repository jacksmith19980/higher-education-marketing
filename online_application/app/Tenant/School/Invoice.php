<?php

namespace App\Tenant\School;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Publishable;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use ForTenants, Publishable, Scope;
    protected $fillable = ['invoice', 'user_id'];
    protected $casts = ['invoice' => 'array'];
}
