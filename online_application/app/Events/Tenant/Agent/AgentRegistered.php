<?php

namespace App\Events\Tenant\Agent;

use App\School;
use App\Tenant\Models\Agent;
use App\Tenant\Models\Agency;
use App\Tenant\Models\Setting;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Events\Dispatchable;

class AgentRegistered
{
    use Dispatchable, SerializesModels;

    public $agent;
    public $school;
    public $agency;
    public $settings;

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
        $this->settings = Setting::byGroup();
    }
}
