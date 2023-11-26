<?php
namespace App\Filters\Submission;

use App\Filters\FiltersAbstrcat;

class Progress extends FiltersAbstrcat
{
    public static function filter($submissions, $progressList = null)
    {
        if ($progressList) {
            $submissions->where(function ($q) use ($progressList) {
                foreach ($progressList as $step_progress) {
                    $progress_array = explode("-", $step_progress);
                    $q->orwhere(function ($query) use ($progress_array) {
                        return $query->whereBetween('submissions.steps_progress_status', $progress_array);
                    });
                }
            });
        }
        return $submissions;
    }
}
