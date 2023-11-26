<?php

namespace App\Repository;

use App\Tenant\Models\Course;

class CourseRepository
{
    public static function all()
    {
        return Course::all();
    }

    public static function bySlug($slug)
    {
        return Course::with('dates', 'addons')->where('slug', $slug)->first();
    }
}
