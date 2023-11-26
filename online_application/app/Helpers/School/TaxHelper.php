<?php

namespace App\Helpers\School;

use App\School;
use Storage;

class TaxHelper
{
    public static function getDefaultCountries()
    {
        return [
            'ca'    => 'Canada',
            'us'    => 'United States',
            'world' => 'Rest Of the World',
        ];
    }

    public function getCountryDefault($country = null)
    {
        $countries = [
                'ca' => [
                    'country_tax' => 5,
                    'regions_tax' => [
                        'qc' => [
                            'title' => 'Quebec',
                            'name'  => 'QST',
                            'value' => 9.975,
                        ],
                        'al' => [
                            'title' => 'Alberta',
                            'name'  => 'PST',
                            'value' => 9.975,
                        ],

                    ],
                ],
            ];
    }
}
