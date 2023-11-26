<?php

namespace App\Listeners\Tenant\Student;

use App\Events\Tenant\Student\ChildAccountCreated;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use App\Tenant\Traits\Integratable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Arr;

class ChildCreatedHook
{
    use Integratable;

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
     * @param  ChildAccountCreated  $event
     * @return void
     */
    public function handle(ChildAccountCreated $event)
    {
        $setting = Setting::byGroup('stages');
        $contact = $event->child;
        $contactType = $event->contactType;

        // Push Stundet to Mautic and assign to Agent
        if ($integration = $this->inetgration()) {
            //$ref = 'VAR-' . (date('Y') + 1) . '-' . $contact->id;

            $data = [
                'firstname' => $contact->first_name,
                'lastname' => $contact->last_name,
                'email' => $contact->email,
                'contact_type' => $contactType,
                'booking_reference'  => $contact->reference,
            ];

            $user = \Auth::guard('student')->user();

            if ($user->role == 'parent') {
                $parent_id = \Auth::guard('student')->user()->id;
            } else {
                $parent_id = \Auth::guard('student')->user()->parent_id;
            }

            if ($parent_id) {
                $parent = Student::where('id', $parent_id)->first();
                // Assign to parent
                if ($parent = $integration->getContact($parent->email)) {
                    if (isset($parent['contacts'])) {
                        $parent_id = Arr::first($parent['contacts'])['id'];
                        if (isset($setting['stages']['new_child_stage'])) {
                            // Update Parent stage
                            $integration->addToStage($parent_id, $setting['stages']['new_child_stage']);
                        }

                        if (isset($parent_id)) {
                            $data['parent'] = $parent_id;
                        }
                    }
                }
            }
            // Get stage
            $stage = (isset($setting['stages']['application_init_stage'])) ? $setting['stages']['application_init_stage'] : null;

            $mauticContact = $integration->createNewContact($data, $contactType, $stage);

            return true;
        }
    }
}
