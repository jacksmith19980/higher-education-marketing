<?php

namespace App\Events\Tenant\Application;

use App\School;
use App\Tenant\Models\Application;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ApplicationCreated
{
    use Dispatchable, SerializesModels;

    public $application;
    public $school;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Application $application, School $school)
    {
        $this->application = $application;
        $this->school = $school;
    }
}
