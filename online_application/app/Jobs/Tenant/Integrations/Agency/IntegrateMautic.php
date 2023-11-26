<?php

namespace App\Jobs\Tenant\Integrations\Agency;

use App\Tenant\Models\Agent;
use App\Tenant\Models\Agency;
use Illuminate\Bus\Queueable;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use App\Tenant\Models\Application;
use App\Tenant\Models\Integration;
use App\Integrations\Mautic\Mautic;
use Illuminate\Queue\SerializesModels;
use App\Tenant\Models\AgencySubmission;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class IntegrateMautic implements ShouldQueue
{
    use Dispatchable, Queueable, SerializesModels;

    public $submission;
    public $agency;
    public $application;
    public $integration;
    public $action;
    public $mautic;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(
        Application $application,
        AgencySubmission $submission,
        Agency $agency,
        Integration $integration,
        $action
    ) {
        $this->application = $application;
        $this->submission = $submission;
        $this->agency = $agency;
        $this->integration = $integration;
        $this->action = $action;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        // Call Event
        $action = $this->action;
        $response = $this->{$action}();
    }

    public function submitAgencyApplication()
    {
        $integrationData = $this->integration->data;
        $mauticData = [];

        $this->mautic = new Mautic($integrationData , false);

        // Get the Company By Email
        $mauticAgencies = $this->mautic->getAgencies($this->agency->email);
        if ($mauticAgencies['total']) {
            $mauticData = reset($mauticAgencies['companies'])['fields']['all'];
            $this->pushAgencyToMautic($mauticData);
        }
    }

    /**
     * Submit Results to Mautic
     */
    protected function pushAgencyToMautic($mauticData = [])
    {

        $agencyId = isset($mauticData['id']) ? $mauticData['id'] : null;
        $pairs = $this->integration->data['mautic_field_pairs'];

        $contactFields=[];

        foreach ($pairs as $pair) {
            $value = '';
            if (isset($this->submission->data[$pair['field']])) {

                if( in_array( $pair['mautic_contact_type'] , ['Agecny' , 'Agency'])) {

                    if(is_array($this->submission->data[$pair['field']])){
                        $value = implode("|" , $this->submission->data[$pair['field']]);
                    }else{
                        $value = $this->submission->data[$pair['field']];
                    }
                    $mauticData[$pair['mautic_field']] = $value;

                }else{
                    $contactFields[$pair['field']] = $this->submission->data[$pair['field']];
                }
            }
        }

        if (!empty($mauticData)) {
            if (isset($agencyId)) {
                unset($mauticData['id']);
                $mauticAgency = $this->mautic->updateAgency($agencyId, $mauticData);

            } else {
                $mauticAgency = $this->mautic->createAgency($mauticData);
            }
        }
        if(isset($mauticAgency['company'])){
            $agent = Agent::find($this->submission->data['agent_id']);
            $contacts = $this->mautic->editApplication($this->submission , $agent, $this->integration->data ,
            array_keys($contactFields));
        }
    }
}
