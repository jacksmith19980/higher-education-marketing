<?php

namespace App\Events\Tenant\Submission;

use App\Tenant\Models\Submission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubmissionContractSent
{
    use Dispatchable, SerializesModels;

    public $submission;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }
}
