<?php

namespace App\Listeners\Tenant;

use App\Events\Tenant\TenantDeleted;
use App\Tenant\Database\DatabaseCreator;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;

class DeleteTenantDatabase
{
    protected $databaseCreator;

    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct(DatabaseCreator $databaseCreator)
    {
        $this->databaseCreator = $databaseCreator;
    }

    /**
     * Handle the event.
     *
     * @param  TenantDeleted  $event
     * @return void
     */
    public function handle(TenantDeleted $event)
    {
        // Create School DB
        if ($this->databaseCreator->delete($event->school)) {
            $event->school->connection()->delete();
            $event->school->delete();
        }
    }
}
