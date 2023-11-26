<?php

namespace App;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

class Plan extends Model
{
    protected $fillable = [
        'title',
        'trial_period',
        'price',
        'is_active',
        'features_description',
        'features',
        'short_description',
    ];

    protected $casts = ['features' => 'array', 'properties' => 'array'];

    public static $modelName = 'plans';

    public static function getModelName()
    {
        return self::$modelName;
    }

    protected static function boot()
    {
        parent::boot();

        // registering a callback to be executed upon the creation of an activity AR
        static::creating(function ($plan) {
            // produce a slug based on the activity title
            $slug = Str::slug($plan->title);

            // check to see if any other slugs exist that are the same & count them
            $count = static::whereRaw("slug RLIKE '^{$slug}(-[0-9]+)?$'")->count();

            // if other slugs exist that are the same, append the count to the slug
            $plan->slug = $count ? "{$slug}-{$count}" : $slug;
        });
    }
}
