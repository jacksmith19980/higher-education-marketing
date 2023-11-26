<?php

namespace App\Events\Tenant\School;

use App\Tenant\Models\Submission;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class UnlockRequestEvent
{
    use Dispatchable;
    use SerializesModels;

    public $submission;

    public function __construct(Submission $submission)
    {
        $this->submission = $submission;
    }
}
