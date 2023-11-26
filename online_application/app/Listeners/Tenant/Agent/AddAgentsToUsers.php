<?php

namespace App\Listeners\Tenant\Agent;

use App\Events\Tenant\Agent\AgentsAddedToAgency;
use App\Tenant\Models\User;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Webpatser\Uuid\Uuid;

class AddAgentsToUsers
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
        $agents = $event->agents;

        // Create Users Account for Each User
        foreach ($agents as $email=>$name) {

            /*$user = User::create([
                'name'      => $name,
                'email'     => $email,
                'password'  => Uuid::generate(1)
            ]);*/
        }
    }
}
