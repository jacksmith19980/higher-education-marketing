<?php

namespace App\Helpers\School;

use App\Tenant\Models\Agency;
use App\Tenant\Models\Application;
use App\Tenant\Traits\Integratable;

/**
 * Agency Helpers
 */
class AgencyHelpers
{
    use Integratable;

    public static function getAgency($companyId)
    {
        $agency = $companyId;
        if ($integration = (new self())->inetgration()) {
            $agency = $integration->getAgency($companyId);
        }

        return $agency;
    }

    public static function getMap($address = null)
    {
        if (! $address) {
            return false;
        }

        return '<iframe width="100%" height="150" id="gmap_canvas" src="https://maps.google.com/maps?q='.$address.'&t=&z=13&ie=UTF8&iwloc=&output=embed" frameborder="0" scrolling="no" marginheight="0" marginwidth="0"></iframe>';
    }

    public static function getFlag($country = null)
    {
        if (! $country) {
            return false;
        }
        $url = 'http://country.io/names.json';
        $countries = json_decode(file_get_contents($url), true);

        if ($countryCode = array_search($country, $countries)) {
            return '<img src="https://www.countryflags.io/'.strtolower($countryCode).'/shiny/32.png">';
        }

        return false;
    }

    public static function getRemoteAgencies()
    {
        $remoteAgencies = [];

        if ($integration = (new self())->inetgration()) {
            $remoteAgencies = $integration->getAgencies();
        }

        return $remoteAgencies;
    }

    public static function getAgencyApplicationsList()
    {
        return  Application::agency()->pluck('title', 'id')->toArray();
    }
}
