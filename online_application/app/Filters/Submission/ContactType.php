<?php
namespace App\Filters\Submission;

use App\Filters\FiltersAbstrcat;

class ContactType extends FiltersAbstrcat
{
    public static function filter($submissions, $contactTypes = null)
    {
        if ($contactTypes) {
            $submissions->whereHas('student', function ($query) use ($contactTypes) {
                return $query->whereIn('stage', $contactTypes);
            });
        }
        return $submissions;
    }
}
