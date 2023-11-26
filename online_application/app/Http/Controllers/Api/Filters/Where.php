<?php
namespace App\Http\Controllers\Api\Filters;

use Illuminate\Http\Request;
use App\Http\Controllers\Api\Exceptions\InvalidFiltersException;

class Where
{
    public static function filter($filters = [] , $items){
        if( count($filters) ){
            foreach($filters as $key => $filter){

                if($filter['exp']== 'like'){
                    $value = '%' . $filter['value'] . '%';
                }else{
                    $value = $filter['value'];
                }
                try {
                    $items = $items->where($key, $filter['exp'] ,  $value);
                } catch (InvalidFiltersException $e) {
                    return $e->getMessage();
                }
            }
        }

        return $items;
    }
}
