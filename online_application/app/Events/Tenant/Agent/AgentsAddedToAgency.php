<?php

namespace App\Events\Tenant\Agent;

use App\School;
use App\Tenant\Models\Agency;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgentsAddedToAgency
{
    use Dispatchable, SerializesModels;

    public $agents;
    public $agency;
    public $school;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Agency $agency, array $agents, School $school)
    {
        $this->agents = array_filter($agents);
        $this->agency = $agency;
        $this->school = $school;
    }
}
