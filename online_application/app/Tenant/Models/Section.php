<?php

namespace App\Tenant\Models;

use App\Tenant\Models\Application;
use App\Tenant\Models\Field;
use App\Tenant\Models\PaymentGateway;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;

class Section extends Model
{
    use ForTenants;

    public static $modelName = 'sections';
    protected $fillable = ['title', 'properties', 'fields_order'];
    protected $casts = [
            'properties'    => 'array',
            'fields_order'  => 'array',
        ];

    public function fields()
    {
        return $this->hasMany(Field::class);
    }

    public function applications()
    {
        return $this->belongsToMany(Application::class);
    }

    public function PaymentGateways()
    {
        return $this->hasMany(PaymentGateway::class);
    }

    public function onlyFields()
    {
        return $this->fields()->where('field_type', '=', 'field');
    }
}
