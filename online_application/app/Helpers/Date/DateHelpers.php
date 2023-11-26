<?php

namespace App\Helpers\Date;

use Carbon\Carbon;
use Illuminate\Support\Facades\App;

class DateHelpers
{
    public static function translateDate($date)
    {
        switch (App::getLocale()) {
            case 'fr':
                setlocale(LC_TIME, 'fr_FR');
                break;
            case 'es':
                setlocale(LC_TIME, 'es_ES');
                break;
        }

        return strftime('%A %d %B %Y', strtotime(\Carbon\Carbon::parse($date)->format('m/d/Y')));
    }

    /**
     * @param $start_date
     * @param $end_date
     * @param $week_day
     * @return array
     */
    public static function allDaysOfWeekInRangeOfDates($start_date, $end_date, $week_day): array
    {
        $days = [];
        $startDate = Carbon::parse($start_date)->modify('this '.$week_day);
        $endDate = Carbon::parse($end_date);

        for ($date = $startDate; $date->lte($endDate); $date->addWeek()) {
            $days[$week_day][] = $date->format('Y-m-d');
        }

        return $days;
    }
}
