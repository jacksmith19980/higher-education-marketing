<?php

namespace App\Listeners\Tenant\Agent;

use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Tenant\Agent\AgentRegistered;
use App\Events\Tenant\Agent\AgentsAddedToAgency;

class AgentsCreatedWebhook
{
    use Integratable;

    protected $settings;

    /**
     * Handle the event.
     *
     * @param  StudentRegistred  $event
     * @return void
     */
    public function __construct()
    {
        $this->settings = Setting::byGroup();
    }

    /**
     * Handle the event.
     *
     * @param  AgentsAddedToAgency  $event
     * @return void
     */
    public function handle($event)
    {
         // check if Sync to Mautic is activated
        if(isset($this->settings['agencies']['mautic_agent_push']) && $this->settings['agencies']['mautic_agent_push'] == 'No'){
            return ;
        }

        $stage = isset($this->settings['agencies']['mautic_agent_stage']) ? $this->settings['agencies']['mautic_agent_stage'] : null;


        $agents = $event->agents;
        $agency = $event->agency;

        // Push Agent To integrations
        if ($integration = $this->inetgration()) {
            if($integration->name() == 'CampusLogin'){
                return;
            }
            foreach ($agents as $email=>$name) {
                $data = [
                    'firstname'     => $name,
                    'email'         => $email,

                    // Default Request Type
                    'request_type'  => isset($this->settings['agencies']['mautic_agent_request_type']) ? $this->settings['agencies']['mautic_agent_request_type'] : null,

                    // Default Contact Type
                    'contact_type'  => isset($this->settings['agencies']['mautic_agent_contact_type']) ? $this->settings['agencies']['mautic_agent_contact_type'] : null,

                    // Default Campus
                    'campus'  => isset($this->settings['agencies']['mautic_agent_default_campus']) ? $this->settings['agencies']['mautic_agent_default_campus'] : null,
                ];


                // Create Agent
                $agent = $integration->createNewContact(array_filter($data), 'Agent', $stage , null);


                if (isset($agent['contact']['id'])) {
                    $integration->addContactToAgencyByEmail($email, $agency->email);
                }
            }
        }
    }
}
