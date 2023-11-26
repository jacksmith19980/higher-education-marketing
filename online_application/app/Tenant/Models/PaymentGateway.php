<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class PaymentGateway extends Model
{
    use ForTenants;

    public static $modelName = 'payment_gateways';

    protected $fillable = [
        'name',
        'slug',
        'properties',
        'key',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function section()
    {
        return $this->belongsTo(Section::class);
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function field()
    {
        return $this->belongsTo(Field::class);
    }
}
