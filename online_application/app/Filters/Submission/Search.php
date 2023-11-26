<?php
namespace App\Filters\Submission;

use App\Filters\FiltersAbstrcat;

class Search extends FiltersAbstrcat
{
    public static function filter($submissions, $string = null)
    {
        if ($string) {
            $submissions->whereHas('student', function ($query) use ($string) {
                return $query->where('first_name', 'like', '%'.$string.'%')
                                ->orWhere('last_name', 'like', '%'.$string.'%')
                                ->orWhere('email', 'like', '%'.$string.'%');
            });
        }
        return $submissions;
    }
}
