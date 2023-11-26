<?php

namespace App\Listeners\Tenant\Student;

use App\Events\Tenant\Student\StudentCreated;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use Auth;
use Illuminate\Support\Arr;

class StudentCreatedHook
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
     * @param  StudentCreated  $event
     * @return void
     */
    public function handle(StudentCreated $event)
    {
        $setting = Setting::byGroup('stages');
        $contact = $event->student;
        $contactType = $event->contactType;
        // Push Stundet to Mautic and assign to Agent
        if ($integration = $this->inetgration()) {
            $ref = 'Ref-'.date('Y').'-'.$contact->id;

            $data = [
                'firstname'          => $contact->first_name,
                'lastname'           => $contact->last_name,
                'email'              => $contact->email,
                'contact_type'       => $contactType,
                'booking_reference'  => $ref,
            ];

            // Assign to agent
            if ($agent = Auth::guard('agent')->user()) {
                $agent = $integration->getContact($agent->email);
                if(isset($agent['contacts']) && count($agent['contacts'])){
                    $data['agent'] = Arr::first($agent['contacts'])['id'];
                }
            }

            $contact = $integration->createNewContact(
                $data,
                $contactType,
               isset( $setting['stages']['application_init_stage']) ? $setting['stages']['application_init_stage'] : null
            );

            return true;
        }
    }
}
