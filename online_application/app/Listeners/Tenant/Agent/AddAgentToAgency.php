<?php

namespace App\Listeners\Tenant\Agent;

use App\Events\Tenant\Agent\AgentRegistered;
use App\Tenant\Traits\Integratable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class AddAgentToAgency
{
    use Integratable;

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
     * @param  AgentRegistered  $event
     * @return void
     */
    public function handle(AgentRegistered $event)
    {
        if ($event->agency) {
            if ($integration = $this->inetgration()) {
                // Add Agent To the Agency
                $agency = $integration->addContactToAgency($event->agent->email, $event->agency);
            }
        }
    }
}
