<?php

namespace App\Helpers\School;

use App\Tenant\Models\Group;
use App\Tenant\Models\Program;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Support\Arr;

class GroupHelpers
{
    public static function getGroupsInArrayOnlyTitleId($data = null, $activeOnly = false)
    {
        if ($data) {
            return Arr::pluck($data, 'title', 'id');
        } else {

            if($activeOnly){

                return Arr::pluck(Group::where('is_active' , true)->select('title', 'id')->get()->toArray(), 'title', 'id');

            }else{
                return Arr::pluck(Group::select('title', 'id')->get()->toArray(), 'title', 'id');
            }

        }
    }

    public static function getGroups(Program $program)
    {
        $semesters = $program->semesters;
        if (count($semesters) > 0) {
            $groups = new Collection([]);
            foreach ($semesters as $semester) {
                $groups = $groups->merge($semester->groups);
            }
        } else {
            $groups = $program->groups;
        }

        return self::getGroupsInArrayOnlyTitleId($groups);
    }
}
