<?php

namespace App\Listeners\Tenant\Agent;

use App\Events\Tenant\Agent\AgentsAddedToAgency;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SendAgentInvitationEmail
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
     * @param  AgentsAddedToAgency  $event
     * @return void
     */
    public function handle(AgentsAddedToAgency $event)
    {
        //
    }
}
