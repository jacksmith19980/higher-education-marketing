<?php

namespace App\Listeners\Tenant\Assistant;

use App\Events\Tenant\Assistant\AssistantEmailRequested;
use App\Helpers\Assistant\AssistantHelpers;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunAssistantRequestedIntegration
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
     * @param  AssistantEmailRequested  $event
     * @return void
     */
    public function handle(AssistantEmailRequested $event)
    {
        $contact = $event->data;
        $setting = Setting::byGroup('stages');
        $assistant = $event->assistant;
        $assistantBuilder = $event->assistantBuilder;

        $programs = $this->extractPrograms($assistant);
        $campus = $this->extractCampus($assistant);

        // Push Agent To integrations
        if ($integration = $this->inetgration()) {
            $request_type = isset($assistantBuilder['properties']['integrations']['mautic']['request_type']) ?
            $assistantBuilder['properties']['integrations']['mautic']['request_type'] : 'VAA Request';

            /*$assistantDetails = urlencode(AssistantHelpers::getAssistantDetails($assistant->properties));*/
            $assistantDetails = AssistantHelpers::getAssistantDetails($assistant->properties);
            //dd($assistantDetails);
            $data = [

                'title'         => $contact['title'],
                'firstname'     => $contact['first_name'],
                'lastname'      => $contact['last_name'],
                'phone'         => $contact['phone'],
                'email'         => $contact['email'],
                'contact_type'  => 'Lead',
                'request_type'  => $request_type,
                'programs'      => $programs,
                'campus'        => $campus,
                'vaa_details' => $assistantDetails,
                'summary_link'  => route('assistants.recuperate.email', [
                                    'school' => request('school'),
                                    'assistant' => $assistant->id,
                                    'user' => $assistant->user_id,
                                ]),
            ];

            // Create Agent
            $contact = $integration->createNewContact($data, 'Lead', $setting['stages']['vaa_email_stage']);

            if (isset($contact['contact']['id'])) {
                $note = [
                    'lead'  => $contact['contact']['id'],
                    'type'  => 'general',
                    'title' => 'Request VAA - '.date('l j F Y'),
                    'text'  =>  $assistantDetails,
                ];
                $integration->addNote($note);
            }
        }
    }

    protected function extractPrograms($assistant)
    {
        $programs = [];
        if (isset($assistant->properties['programs'])) {
            foreach ($assistant->properties['programs'] as $program) {
                $programs[] = $program['title'];
            }
        }

        return $programs;
    }

    protected function extractCampus($assistant)
    {
        $campus = '';
        if (isset($assistant->properties['campuses'])) {
            foreach ($assistant->properties['campuses'] as $campus) {
                $campus = $campus['title'];
            }
        }

        return $campus;
    }
}
