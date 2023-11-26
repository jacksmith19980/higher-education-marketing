<?php

namespace App\Helpers\School;

use App\Tenant\Models\Course;
use Illuminate\Support\Arr;

class CourseHelpers
{
    public static function getCoursesInArrayOnlyTitleId($array = null)
    {
        if ($array === null) {
            return Arr::pluck(Course::all('title', 'id')->toArray(), 'title', 'id');
        } else {
            return Arr::pluck($array, 'title', 'id');
        }
    }
}
