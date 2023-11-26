<?php

namespace App\Helpers\School;

use App\Tenant\Models\Program;
use App\Tenant\Models\Semester;
use Illuminate\Support\Arr;

class SemesterHelpers
{
    public static function getSemestersInArrayOnlyTitleId($data = null)
    {
        if ($data) {
            return Arr::pluck($data, 'title', 'id');
        } else {
            return Arr::pluck(Semester::select('title', 'id')->get()->toArray(), 'title', 'id');
        }
    }

    public static function programHaveSemesters(Program $program)
    {
        if (count($program->semestereable) > 0) {
            return $program->semestereable;
        } else {
            return false;
        }
    }
}
