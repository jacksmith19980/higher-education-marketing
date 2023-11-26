<?php

namespace App\Listeners\Tenant\Application;

use App\Events\Tenant\Application\ApplicationCreated;
use Illuminate\Support\Facades\Artisan;

class AddDefaultSections
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  ApplicationCreated  $event
     * @return void
     */
    public function handle(ApplicationCreated $event)
    {
        // Run Tenants Seeders to seed Sections table with the default sections
        Artisan::call('tenants:seed', [
            '--tenants' => [$event->school->id],
        ]);
    }
}
