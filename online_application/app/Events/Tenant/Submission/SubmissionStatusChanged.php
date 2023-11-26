<?php

namespace App\Events\Tenant\Submission;

use App\Tenant\Models\Submission;
use Illuminate\Queue\SerializesModels;
use App\Tenant\Models\SubmissionStatus;
use Illuminate\Foundation\Events\Dispatchable;

class SubmissionStatusChanged
{
    use Dispatchable, SerializesModels;

    public $submission;
    public $submissionStatus;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Submission $submission ,SubmissionStatus $submissionStatus)
    {
        $this->submission = $submission;
        $this->submissionStatus = $submissionStatus;
    }
}

?>
