<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class InvoicePayments extends Model
{
    use ForTenants;

    public static $modelName = 'invoice_payments';

    protected $fillable = [
        'uid',
        'amount_paid',
        'status',
        'payment_gateway',
        'payment_method',
        'properties', 'invoice_id',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    public function getPaymentMethodAttribute()
    {
        return $this->invoice ? $this->invoice->payment_method : null;
    }
}
