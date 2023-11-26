<?php

namespace App\Events\Tenant\Assistant;

use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FollowUpRequested
{
    use Dispatchable, SerializesModels;

    public $type;
    public $request;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($type, $request)
    {
        $this->type = $type;
        $this->request = $request;
    }
}
