<?php

namespace App\Listeners\Tenant;

use App\Events\Tenant\TenantDatabaseCreated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Facades\Artisan;

class SetupTenantDatabase
{
    /**
     * Handle the event.
     *
     * @param  TenantDatabaseCreated  $event
     * @return void
     */
    public function handle(TenantDatabaseCreated $event)
    {
        Artisan::call('tenants:migrate', [
         '--tenants' => [$event->tenant->id],
        ]);
    }
}
