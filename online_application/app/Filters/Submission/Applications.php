<?php
namespace App\Filters\Submission;

use App\Filters\FiltersAbstrcat;

class Applications extends FiltersAbstrcat
{
    public static function filter($submissions, $applicationIDs = null)
    {
        if ($applicationIDs) {
            $submissions = $submissions->whereIn('application_id', $applicationIDs);
        }
        return $submissions;
    }
}
