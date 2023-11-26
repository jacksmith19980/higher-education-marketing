<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;

class Integration extends Model
{
    use ForTenants;

    public static $modelName = 'integrations';

    protected $fillable = ['title', 'event', 'data'];

    protected $casts = [
                        'events'    => 'array',
                        'data'      => 'array',
                        ];

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function scopeByEvent(Builder $builder, $event)
    {
        $builder->where('event', $event);
    }
}
