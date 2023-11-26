<?php

namespace App\Tenant\Traits;

use Illuminate\Database\Eloquent\Builder;

/**
 * Shared Publish Process
 */
trait Publishable
{
    public function scopePublished(Builder $builder)
    {
        return $builder->where('published', true);
    }
}
