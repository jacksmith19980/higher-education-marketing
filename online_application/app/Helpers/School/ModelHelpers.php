<?php

namespace App\Helpers\School;

use Illuminate\Support\Arr;

class ModelHelpers
{
    public static function convertFirstNameLastnameInNameAssocWithId($collection)
    {
        $return = $collection->map(
            function ($person) {
                return [$person['id'], $person['first_name'].' '.$person['last_name']];
            }
        )->reduce(
            function ($assoc, $keyValuePair) {
                list($key, $value) = $keyValuePair;
                $assoc[$key] = $value;

                return $assoc;
            }
        );

        return $return ? $return : [];
    }

    public static function arrayToArrayOnlyWithTitleId($array)
    {
        return Arr::pluck($array, 'title', 'id');
    }
}
