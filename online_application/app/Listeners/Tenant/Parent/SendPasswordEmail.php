<?php

namespace App\Listeners\Tenant\Parent;

use App\Events\Tenant\Parent\ParentRegistred;
use App\Mail\Tenant\ParentPasswordEmail;
use Illuminate\Contracts\Queue\ShouldQueue;
use Mail;

class SendPasswordEmail
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
     * @param  ParentRegistred  $event
     * @return void
     */
    public function handle(ParentRegistred $event)
    {
        if (! $event->password) {
            return false;
        }

        //Send Activation Email
    //Mail::to($event->parent->email)->send(new ParentPasswordEmail($event->parent , $event->school, $event->password));
    }
}
