<?php
namespace App\Filters;

trait HasFilters
{

    /**
     * Filter submissions
     */
    public function filter($folder, $filters = [], $submissions)
    {
        foreach ($filters as $key=>$value) {
            $class = "App\\Filters\\".ucwords($folder)."\\" . str_replace(" ", "", ucwords(str_replace("_", " ", $key)));
            if (class_exists($class)) {
                $submissions = $class::filter($submissions, $value);
            }
        }
        return $submissions;
    }
}
