<?php

namespace App\Listeners\Tenant\Agency;

use App\Events\Tenant\Agency\AgencyIsUpdated;
use App\Tenant\Traits\Integratable;

class AgencyUpdatedWebhook
{
    use Integratable;

    /**
     * Handle the event.
     *
     * @param  AgencyIsUpdated  $event
     * @return void
     */
    public function handle(AgencyIsUpdated $event)
    {
        // Create Mautic Agency
        if ($integration = $this->inetgration()) {
            $agency = $event->agency;

            $data = [
                'companyname'           => $agency->name,
                'companyemail'          => $agency->email,
                'companyphone'          => $agency->phone,
                'companycity'           => $agency->city,
                'companycountry'        => $agency->country,
                'companyaddress1'       => $agency->address,
                'companyzipcode'        => $agency->postal_code,
                'companydescription'    => $agency->description,
            ];

            $mauticAgency = $integration->getAgencies($agency->email);

            if (isset($mauticAgency['companies']) && count($mauticAgency['companies'])) {

                // Edit company
                $integration->editAgency($data, reset($mauticAgency['companies'])['id']);
            } else {

                // Create New company
                $integration->creatAgency($data);
            }
        }
    }
}
