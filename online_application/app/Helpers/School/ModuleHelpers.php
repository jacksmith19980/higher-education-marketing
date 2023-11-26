<?php

namespace App\Helpers\School;

use Illuminate\Support\Arr;

class ModuleHelpers
{
    public static function getModuleInArrayOnlyTitleId($collection)
    {
        return Arr::pluck($collection, 'title', 'id');
    }
}
