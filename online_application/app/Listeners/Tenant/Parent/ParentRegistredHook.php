<?php

namespace App\Listeners\Tenant\Parent;

use App\Events\Tenant\Parent\ParentRegistred;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use Illuminate\Contracts\Queue\ShouldQueue;

class ParentRegistredHook
{
    use Integratable;

    /**
     * Handle the event.
     *
     * @param  ParentRegistred  $event
     * @return void
     */
    public function handle(ParentRegistred $event)
    {
        $settings = Setting::byGroup('stages');

        $stage = (isset($settings['stages']['new_parent_stage'])) ? $settings['stages']['new_parent_stage'] : null;

        if ($integration = $this->inetgration()) {
            $contact = $event->parent;
            $contactType = $event->contactType;

            $integration->createNewContact([
                'firstname' => $contact->first_name,
                'lastname' => $contact->last_name,
                'email' => $contact->email,
            ], $contactType, $stage);

            return true;
        }
    }
}
