<?php

namespace App\Tenant\Traits;

/**
 * Shared Publish Process
 */
trait ExtractProperties
{
    public function getProgramDates($startDates, $endDates, $type)
    {
        dd($type);
        $dates = [];

        if (is_array($startDates) && is_array($endDates)) {
            foreach ($startDates as $key=>$value) {
                $dates[$key]['start'] = $value;
                $dates[$key]['end'] = $endDates[$key];
            }
        }

        return $dates;
    }
}
