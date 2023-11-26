<?php

namespace App\Tenant\Traits;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Support\Str;

trait Slugable
{
    public function slugify($string, $model)
    {
        if ($item = $model::where('slug', Str::slug($string))->first()) {
            return $this->slugify(Str::slug($string).'-'.rand(1, 50), $model);
        }

        return Str::slug($string);
    }
}
