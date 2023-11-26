<?php

namespace App\Listeners\Tenant\Agency;

use App\Events\Tenant\Agency\AgencyIsCreated;
use App\Tenant\Traits\Integratable;

class AgencyCreatedWebhook
{
    use Integratable;

    /**
     * Handle the event.
     *
     * @param  AgencyIsCreated  $event
     * @return void
     */
    public function handle(AgencyIsCreated $event)
    {
        // Create Mautic Agency
        if ($integration = $this->inetgration()) {
            $agency = $event->agency;

            $integration->creatAgency(
                [
                    'companyname'           => $agency->name,
                    'companyemail'          => $agency->email,
                    'companyphone'          => $agency->phone,
                    'companycity'           => $agency->city,
                    'companycountry'        => $agency->country,
                    'companydescription'    => $agency->description,
                ]
            );
        }
    }
}
