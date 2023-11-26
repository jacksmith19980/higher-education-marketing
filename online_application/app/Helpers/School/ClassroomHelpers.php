<?php

namespace App\Helpers\School;

use App\Tenant\Models\Classroom;
use Illuminate\Support\Arr;

class ClassroomHelpers
{
    public static function getClassroomsInArrayOnlyTitleId()
    {
        return Arr::pluck(Classroom::select('title', 'id')->get()->toArray(), 'title', 'id');
    }

    public static function getClassroomsInArrayAssociativeOnlyTitleId()
    {
        return Arr::pluck(Classroom::select('title', 'id')->get()->toArray(), 'title', 'id');
    }
}
