<?php
namespace App\Filters\Submission;

use App\Filters\FiltersAbstrcat;

class Dates extends FiltersAbstrcat
{
    public static function filter($submissions, $dates = null)
    {
        if(gettype($dates) == 'object' ){
            $dates = (array)$dates;
        }

        if ($dates) {
            $start_date = date('Y-m-d 00:00:00', strtotime($dates['start_date']));
            $end_date = date('Y-m-d 23:59:59', strtotime($dates['end_date']));
            $submissions->whereBetween('submissions.created_at', [$start_date, $end_date]);
        }
        return $submissions;
    }
}
