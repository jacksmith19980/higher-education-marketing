<?php

namespace App\Listeners\Tenant\Agent;

use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use App\Events\Tenant\Agent\AgentRegistered;

class AgentCreatedWebhook
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
     * @param  AgentRegistered  $event
     * @return void
     */
    public function handle(AgentRegistered $event)
    {
        $params =
        request()->except(['_token',"school","agency_name","agency_email","first_name","last_name","email","password","password_confirmation","is_admin","agency_id"]);




        // check if Sync to Mautic is activated
        if(isset($this->settings['agencies']['mautic_agent_push']) && $this->settings['agencies']['mautic_agent_push'] == 'No'){
            return ;
        }

        $stage = isset($this->settings['agencies']['mautic_agent_stage']) ? $this->settings['agencies']['mautic_agent_stage'] : null;

        // Push Agent To integrations
        if ($integration = $this->inetgration()) {

            if($integration->name() == 'CampusLogin'){
                return;
            }

            $data = [
                    'firstname'     => $event->agent->name,
                    'email'         => $event->agent->email,
                    // Default Request Type
                    'request_type'  => isset($this->settings['agencies']['mautic_agent_request_type']) ? $this->settings['agencies']['mautic_agent_request_type'] : null,

                    // Default Contact Type
                    'contact_type'  => isset($this->settings['agencies']['mautic_agent_contact_type']) ? $this->settings['agencies']['mautic_agent_contact_type'] : null,

                    // Default Campus
                    'campus'  => isset($this->settings['agencies']['mautic_agent_default_campus']) ? $this->settings['agencies']['mautic_agent_default_campus'] : null,

                ];

            $data = $data + $params;


            // Create Agent
            $agent = $integration->createNewContact(array_filter($data), 'Agent', $stage , null);

            if (isset($agent['contact']['id'])) {
                $integration->addContactToAgencyByEmail($event->agent->email, $event->agency->email);
            }
        }
    }
}
