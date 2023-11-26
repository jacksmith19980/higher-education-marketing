<?php

namespace App\Events\Tenant\Agent;

use App\School;
use App\Tenant\Models\Agency;
use App\Tenant\Models\Agent;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class InvitationEmailRequested
{
    use Dispatchable, SerializesModels;

    public $agent;
    public $school;
    public $agency;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Agent $agent, School $school, Agency $agency)
    {
        $this->agent = $agent;
        $this->school = $school;
        $this->agency = $agency;
    }
}
