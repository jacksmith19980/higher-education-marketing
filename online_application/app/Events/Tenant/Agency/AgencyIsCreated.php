<?php

namespace App\Events\Tenant\Agency;

use App\Tenant\Models\Agency;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AgencyIsCreated
{
    use Dispatchable, SerializesModels;

    public $agency;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Agency $agency)
    {
        $this->agency = $agency;
    }
}
