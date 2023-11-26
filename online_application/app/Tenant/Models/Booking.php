<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Publishable;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use ForTenants;
    use Publishable;
    use Scope;

    protected $fillable = ['invoice', 'details', 'user_id', 'quotation_id', 'object'];
    protected $casts = ['invoice' => 'array'];

    public function quotation()
    {
        return $this->belongsTo(Quotation::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function promocodeables()
    {
        return $this->morphToMany(Promocode::class, 'promocodeable');
    }
}
