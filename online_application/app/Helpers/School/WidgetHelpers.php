<?php

namespace App\Helpers\School;

use App\Tenant\Models\Agency;
use App\Tenant\Traits\Integratable;

/**
 * Widget Helpers
 */
class WidgetHelpers
{
    use Integratable;

    public static function get($applications, $students, Agency $agency)
    {
        $widget = self::getApplicantByAgents($applications);

        dd($widget);
    }

    public static function getApplicantByAgents($applications)
    {
        return $applications;
    }
}
