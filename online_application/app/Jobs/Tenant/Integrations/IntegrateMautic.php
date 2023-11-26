<?php

namespace App\Jobs\Tenant\Integrations;

use App\School;
use Illuminate\Bus\Queueable;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use App\Tenant\Models\Application;
use App\Tenant\Models\Integration;
use App\Integrations\Mautic\Mautic;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class IntegrateMautic implements ShouldQueue
{
    use Dispatchable;
    use Queueable;
    use SerializesModels;

    public $submission;
    public $student;
    public $application;
    public $integration;
    public $action;
    public $setting;
    public $response;
    public $fields;
    public $mautic;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Application $application, Submission $submission, Student $student, Integration $integration, $action, $setting = [], $fields = [])
    {
        $this->application = $application;
        $this->submission = $submission;
        $this->student = $student;
        $this->integration = $integration;
        $this->action = $action;
        $this->setting = $setting;
        $this->fields = $fields;
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
        $this->response = $this->{$action}();
    }

    public function getResponse()
    {
        return $this->response;
    }

    public function submissionApproved()
    {
    }

    public function submitApplication()
    {
        $integrationData = $this->integration->data;
        $invoiceURL = null;
        $school = School::byUuid(session('tenant'))->first();

        if ($invoice = $this->student->invoices()->first()) {
            $invoiceURL = route('invoice.pay', [
                'school' => $school,
                'invoice' => $invoice]);
        }
        $mautic = new Mautic($integrationData, false);
        $this->response = $mautic->submitApplication($this->submission, $this->student, $integrationData, $this->application, $this->setting, $invoiceURL);
    }

    public function editApplication()
    {
        $integrationData = $this->integration->data;

        $mautic = new Mautic($integrationData,false);

        $this->response = $mautic->editApplication($this->submission, $this->student, $integrationData, $this->fields);
    }



    public function submitAgencyApplication($agency)
    {
        $integrationData = $this->integration->data;
        $mauticData = [];

        $this->mautic = new Mautic($integrationData , false);

        // Get the Company By Email
        $mauticAgencies = $this->mautic->getAgencies($agency->email);
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

        $contactData=[];

        foreach ($pairs as $pair) {
            if (isset($this->submission->data[$pair['field']])) {

                if( in_array( $pair['mautic_contact_type'] , ['Agecny' , 'Agency'])) {

                    $mauticData[$pair['mautic_field']] = $this->submission->data[$pair['field']];
                }else{
                    $contactData[$pair['mautic_contact_type']][$pair['mautic_field']] =
                    $this->submission->data[$pair['field']];
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
    }
}
