<?php

namespace App\Events\Tenant\Application;

use App\Events\Tenant\Application\ApplicationSubmissionEvent;
use App\Tenant\Models\Application;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationSubmissionUpdated extends ApplicationSubmissionEvent
{
}
