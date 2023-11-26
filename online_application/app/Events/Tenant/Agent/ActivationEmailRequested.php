<?php

namespace App\Events\Tenant\Agent;

use App\School;
use App\Tenant\Models\Agent;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class ActivationEmailRequested
{
    use Dispatchable, SerializesModels;

    public $agent;
    public $school;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Agent $agent, School $school)
    {
        $this->agent = $agent;
        $this->school = $school;
    }
}
