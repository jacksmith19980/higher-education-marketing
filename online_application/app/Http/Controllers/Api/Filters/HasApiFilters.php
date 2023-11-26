<?php

namespace App\Http\Controllers\Api\Filters;

trait HasApiFilters
{

    protected function filter($filters = [] , $items){

        foreach ($filters as $key=>$filter) {
            $class = "App\\Http\\Controllers\\Api\Filters\\" . ucwords($key);
            if(class_exists($class)){
                $items = $class::filter($filter , $items );
            }
        }
        return $items;
    }
}
