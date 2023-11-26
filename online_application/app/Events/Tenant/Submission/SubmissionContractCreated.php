<?php

namespace App\Events\Tenant\Submission;

use App\Tenant\Models\Submission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class SubmissionContractCreated
{
    use Dispatchable, SerializesModels;

    public $submission;
    public $data;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Submission $submission, $data)
    {
        $this->submission = $submission;
        $this->data = $data;
    }
}
