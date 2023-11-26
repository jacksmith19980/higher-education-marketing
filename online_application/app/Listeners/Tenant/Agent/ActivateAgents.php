<?php

namespace App\Listeners\Tenant\Agent;

use App\Tenant\Models\Agent;
use App\Events\Tenant\Agency\AgencyIsApproved;

class ActivateAgents
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param AgencyIsApproved $event
     * @return void
     */
    public function handle(AgencyIsApproved $event)
    {
        $agents = $event->agency->agents()->whereNotNull('activation_token')->get();
        if($agents->count()){
            foreach($agents as $agent){
                $agent->update([
                    'activation_token'  => null,
                    'active'            => true,
                ]);
            }
        }
    }
}
