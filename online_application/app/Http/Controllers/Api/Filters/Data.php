<?php
namespace App\Http\Controllers\Api\Filters;


class Data
{
    public static function filter($filters = [] , $items){
        if( count($filters) ){
            foreach($filters as $key => $value){
                try {
                    $items = $items->whereJsonContains("data->$key", $value);
                } catch (\Exception $e) {
                    return $e->getMessage();
                }
            }
        }
        return $items;
    }
}
