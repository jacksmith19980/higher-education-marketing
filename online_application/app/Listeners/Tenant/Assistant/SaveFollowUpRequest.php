<?php

namespace App\Listeners\Tenant\Assistant;

use App\Events\Tenant\Assistant\FollowUpRequested;
use App\Tenant\Models\Admission;
use App\Tenant\Models\Followup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SaveFollowUpRequest
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
     * @param  FollowUpRequested  $event
     * @return void
     */
    public function handle(FollowUpRequested $event)
    {
        $followup = new Followup;
        $followup->name = $event->request['name'];
        $followup->email = $event->request['email'];
        $followup->phone = $event->request['phone'];
        $followup->type = $event->type;
        $followup->status = $event->request['status'];
        $followup->properties = $event->request;

        // Get the Admission Person ID
        //$admission = $this->getAdmission();
        $event->request['admission']->followups()->save($followup);
    }
}
