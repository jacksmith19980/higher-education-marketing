<?php

namespace App\Listeners\Tenant\Agent;

use App\Events\Tenant\Agent\AgentsAddedToAgency;
use App\Mail\Tenant\AgentActivationMail;
use App\Mail\Tenant\AgentInvitationMail;
use App\Tenant\Models\Agent;
use Hash;
use Illuminate\Support\Str;
use Mail;
use Session;

class CreateAgents
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
     * @param AgentsAddedToAgency $event
     * @return void
     */
    public function handle(AgentsAddedToAgency $event)
    {
        $agents = $event->agents;
        $agency = $event->agency;
        $is_admin = false;
        $rol = 'Regular Agent';

        if (! $agency->agents->count()) {
            $is_admin = true;
            $rol = 'Agency Admin';
        }

        $added = [];
        $notAdded = [];
        // Create Users Account for Each User
        foreach ($agents as $email => $name) {
            $agent = Agent::firstOrNew(['email' => $email]);

            // If Agent Not Exist
            if (! $agent->password) {
                $added[] = $name;

                $password = Str::random(8);
                $agent->first_name = $name;
                $agent->last_name = ' ';
                $agent->password = Hash::make($password);
                $agent->activation_token = Str::random(255);
                $agent->agency_id = $agency->id;
                $agent->is_admin = $is_admin;
                $agent->roles = $rol;
                $agent->save();

                // Send Invitation Email
                try {
                    Mail::to($agent->email)->send(new AgentInvitationMail($agent, $agency, $event->school, $password));
                } catch (\Exception $e) {
                }
            } else {
                $notAdded[] = $name;
            }
        }
        Session::flash('agents', [
            'added' => $added,
            'notAdded' => $notAdded,
        ]);
    }
}
