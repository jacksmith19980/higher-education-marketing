<?php

namespace App\Listeners\Tenant\Agent;

use App\Events\Tenant\Agent\AgentRegistered;
use App\Mail\Tenant\AgentActivationMail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendAgentActivationEmail
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
     * @param  AgentRegistered  $event
     * @return void
     */
    public function handle(AgentRegistered $event)
    {

        if(isset($event->settings['agencies']['automatically_approve_agencies']) &&
        $event->settings['agencies']['automatically_approve_agencies'] == "Yes"){
            //Send Activation Email
            Mail::to($event->agent->email)->send(new AgentActivationMail($event->agent, $event->school));

        }
    }
}
