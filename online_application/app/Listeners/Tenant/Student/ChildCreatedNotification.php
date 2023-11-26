<?php

namespace App\Listeners\Tenant\Student;

use App\Events\Tenant\Student\ChildAccountCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ChildCreatedNotification
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
     * @param  ChildAccountCreated  $event
     * @return void
     */
    public function handle(ChildAccountCreated $event)
    {
        //
    }
}
