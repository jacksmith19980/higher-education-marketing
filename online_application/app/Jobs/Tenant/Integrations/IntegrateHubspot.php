<?php

namespace App\Jobs\Tenant\Integrations;

use App\School;
use Illuminate\Bus\Queueable;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use App\Tenant\Models\Application;
use App\Tenant\Models\Integration;
use App\Integrations\Hubspot\Hubspot;
use Illuminate\Queue\SerializesModels;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class IntegrateHubspot implements ShouldQueue
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


        $hs = new Hubspot($integrationData , false);
        $this->response = $hs->submitApplication($this->submission, $this->student, $integrationData, $this->application, $this->setting, $invoiceURL);
    }

    public function editApplication()
    {
        $integrationData = $this->integration->data;

       $hs = new Hubspot($integrationData , false);

        $this->response = $hs->editApplication($this->submission, $this->student, $integrationData, $this->fields);
    }
}
