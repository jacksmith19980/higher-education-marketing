<?php

namespace App\Tenant\Traits;

use Illuminate\Database\Eloquent\Builder;

trait Scope
{
    /**
     * Find Item By Slug
     * @param  Builder $builder [description]
     * @param  [type]  $slug    [description]
     * @return [type]           [description]
     */
    public function scopeBySlug(Builder $builder, $slug)
    {
        $builder->where('slug', $slug);
    }

    public function scopeByName(Builder $builder, $name)
    {
        $builder->where('name', $name);
    }

    public function scopeisActive(Builder $builder)
    {
        $builder->where('is_active', true);
    }
}
