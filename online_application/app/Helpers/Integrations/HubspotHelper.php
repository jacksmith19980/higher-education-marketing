<?php

namespace App\Helpers\Integrations;

use App\Tenant\Traits\Integratable;

class HubspotHelper
{
    use Integratable;


    public static function getCustomProperties($object = 'student')
    {
        if ($hubspot = (new static)->inetgration()) {
            return $hubspot->getCustomFields($object);
        }
        return [];
    }

}
?>
