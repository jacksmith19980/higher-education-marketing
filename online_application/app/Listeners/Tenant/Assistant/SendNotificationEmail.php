<?php

namespace App\Listeners\Tenant\Assistant;

use App\Events\Tenant\Assistant\FollowUpScheduled;
use App\Mail\Tenant\CallBackRequestEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendNotificationEmail
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
     * @param  FollowUpScheduled  $event
     * @return void
     */
    public function handle(FollowUpScheduled $event)
    {
        if ($event->type == 'call') {
            Mail::to($event->request['admission']->email)
                ->send(new CallBackRequestEmail($event->request));
        }
    }
}
