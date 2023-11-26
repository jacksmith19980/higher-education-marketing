<?php

namespace App\Events\Tenant\Assistant;

use App\Tenant\Models\Assistant;
use App\Tenant\Models\AssistantBuilder;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class AssistantEmailRequested
{
    use Dispatchable,  SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $data;
    public $assistantBuilder;
    public $assistant;

    public function __construct(array $data, AssistantBuilder $assistantBuilder, Assistant $assistant)
    {
        $this->data = $data;
        $this->assistantBuilder = $assistantBuilder;
        $this->assistant = $assistant;
    }
}
