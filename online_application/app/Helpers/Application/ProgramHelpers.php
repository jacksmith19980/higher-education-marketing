<?php

namespace App\Helpers\Application;

use Illuminate\Support\Arr;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Program;
use App\Tenant\Models\Setting;

/**
 * ProgramHelpers Helper
 */
class ProgramHelpers
{
    public static function campusesList()
    {
        return Campus::pluck('title', 'slug')->all();
    }

    public static function getProgramInArrayOnlyTitleId()
    {
        return Arr::pluck(Program::all()->toArray(), 'title', 'id');
    }


    public static function getProgramTypes()
    {
        $settings = Setting::byGroup('education');
        if (isset($settings['education']['degrees'])) {
            return $settings['education']['degrees'];
        }
        return  [
                    'Certificate' => 'Certificate',
                    'Diploma'     => 'Diploma',
                    'Associate'   => 'Associate',
                    'Bachelor'    => 'Bachelor',
                    'Master'      => 'Master',
                    'Doctorate'   => 'Doctorate',
                    'Non-Degree'  => 'Non-Degree',

        ];
    }
}
