<?php

namespace App\Listeners\Tenant\Agent;

use App\Events\Tenant\Agent\ActivationEmailRequested;
use App\Mail\Tenant\AgentActivationMail;
use Mail;

class ReSendAgentActivationEmail
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
     * @param  ActivationEmailRequested  $event
     * @return void
     */
    public function handle(ActivationEmailRequested $event)
    {
        //Send Activation Email
        Mail::to($event->agent->email)->send(new AgentActivationMail($event->agent, $event->school));
    }
}
