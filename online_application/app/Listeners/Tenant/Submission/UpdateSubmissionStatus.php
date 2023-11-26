<?php

namespace App\Listeners\Tenant\Submission;

use App\Events\Tenant\Submission\SubmissionContractSent;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class UpdateSubmissionStatus
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
     * @param  SubmissionContractSent  $event
     * @return void
     */
    public function handle(SubmissionContractSent $event)
    {
        //dd($event->submission);
    }
}
