<?php

namespace App\Tenant\Models;

use App\Tenant\School\Invoice;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class InvoiceStatus extends Model
{
    use ForTenants;
    protected $fillable = ['status', 'properties'];
    protected $casts = ['properties' => 'array'];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }
}
