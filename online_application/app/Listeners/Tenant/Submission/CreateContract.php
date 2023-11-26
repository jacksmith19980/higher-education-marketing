<?php

namespace App\Listeners\Tenant\Submission;

use App\Events\Tenant\Submission\SubmissionContractCreated;
use App\Tenant\Models\Contract;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateContract
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
     * @param  SubmissionContractCreated  $event
     * @return void
     */
    public function handle(SubmissionContractCreated $event)
    {
        //$contract = Contract::create($event->data);
        $contract = Contract::updateOrCreate(
            [
                'uid'           => $data['uid'],
            ],
            $data
        );
        dd($contract);
    }
}
