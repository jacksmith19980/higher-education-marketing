<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Publishable;
use App\Tenant\Traits\Scope;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Quotation extends Model
{
    use HasFactory;
    use ForTenants;
    use Publishable;
    use Scope;

    public static $modelName = 'quotations';
    protected $fillable = ['title', 'slug', 'properties', 'description'];
    protected $casts = ['properties' => 'array'];

    public function getRouteKeyName()
    {
        return 'slug';
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function booking()
    {
        return $this->hasMany(Booking::class);
    }

    public function promocodeables()
    {
        return $this->morphToMany(Promocode::class, 'promocodeable');
    }
}
