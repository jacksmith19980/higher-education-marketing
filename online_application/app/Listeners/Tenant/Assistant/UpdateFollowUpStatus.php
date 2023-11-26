<?php

namespace App\Listeners\Tenant\Assistant;

use App\Events\Tenant\Assistant\FollowUpUpdate;
use App\Tenant\Models\Followup;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateFollowUpStatus
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
     * @param  FollowUpUpdate  $event
     * @return void
     */
    public function handle(FollowUpUpdate $event)
    {
        $followUp = Followup::where([
            'name'      => $event->request->name,
            'email'     => $event->request->email,
            'phone'     => $event->request->phone,
            'type'      => $event->type,
        ])->orderBy('id', 'DESC')->first();

        $followUp->update([
            'status' => $event->status,
        ]);
    }
}
