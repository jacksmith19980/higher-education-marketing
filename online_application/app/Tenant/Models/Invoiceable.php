<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Invoiceable extends Model
{
    use ForTenants;

    public static $modelName = 'invoiceables';

    protected $fillable = [
        'uid',
        'amount',
        'title',
        'properties',
        'invoice_id',
        'student_id',
        'invoiceable_id',
        'invoiceable_type',
    ];

    protected $casts = [
        'properties' => 'array',
    ];
}
