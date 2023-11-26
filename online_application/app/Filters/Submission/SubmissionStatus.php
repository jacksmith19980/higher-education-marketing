<?php
namespace App\Filters\Submission;

use App\Filters\FiltersAbstrcat;

class SubmissionStatus extends FiltersAbstrcat
{
    public static function filter($submissions, $submissionStatuses = null)
    {
        if ($submissionStatuses) {
            $submissions = $submissions->whereIn('status', $submissionStatuses);
        }
        return $submissions;
    }
}
