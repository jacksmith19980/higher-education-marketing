<?php

namespace App\Listeners\Tenant\Agent;

use App\Events\Tenant\Agent\InvitationEmailRequested;
use App\Mail\Tenant\AgentInvitationMail;
use Hash;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Str;
use Mail;

class ReSendAgentInvitationEmail
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
     * @param  InvitationEmailRequested  $event
     * @return void
     */
    public function handle(InvitationEmailRequested $event)
    {
        $newPassword = Str::random(8);
        // Update the Password
        $event->agent->password = Hash::make($newPassword);
        $event->agent->save();

        // Send Invitation Email
        try {
            Mail::to($event->agent->emaill)->send(new AgentInvitationMail($event->agent, $event->agency, $event->school, $newPassword));
        } catch (\Exception $e) {
        }
    }
}
