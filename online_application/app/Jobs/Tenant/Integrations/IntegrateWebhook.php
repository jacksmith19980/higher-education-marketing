<?php

namespace App\Jobs\Tenant\Integrations;

use App\Http\Controllers\Tenant\PDFController;
use App\Integrations\Webhook\Webhook;
use App\Tenant\Models\Application;
use App\Tenant\Models\Integration;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IntegrateWebhook implements ShouldQueue
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
    public function __construct(Application $application, Submission $submission, Student $student, Integration $integration, $action, $setting, $fields = [])
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
        $integrationData = $this->integration->data;
        list($fields, $submissionData) = $this->constructSubmissionData($this->submission->data);
        $submissionData['action'] = 'application.approved';
        $submissionData['status'] = $this->submission->status;
        $submissionData['properties'] = $this->submission->properties;
        $webhook = new Webhook($integrationData);
        $this->response = $webhook->request(null, null, $submissionData, [
            'headers'   => null,
            'json'      => false,
        ]);
    }

    public function submitApplication()
    {
        $integrationData = $this->integration->data;
        list($fields, $submissionData) = $this->constructSubmissionData($this->submission->data);

        $invoiceURL = null;
        if ($invoice = $this->student->invoices()->first()) {
            $invoiceURL = route('invoice.pay', ['school' => request('school'), 'invoice' => $invoice]);
        }

        $submissionData['invoice'] = $invoiceURL;
        $submissionData['action'] = 'application.submitted';
        $submissionData['status'] = $this->submission->status;
        $submissionData['properties'] = $this->submission->properties;
        $submissionData['uid'] = $this->submission->student->id;

        if ($this->submission->status == 'Submitted') {
            $pdf = app(PDFController::class)->pdf($this->submission, 'email' , true);
            $submissionData['application_pdf'] = $pdf;
            // Attach Submission Date
            $submissionData['submission_date'] = date('Y-m-d h:i:s');
        }

        $webhook = new Webhook($integrationData);
        $this->response = $webhook->request(null, null, $submissionData, [
            'headers'   => null,
            'json'      => false,
        ]);
    }

    public function editApplication()
    {
        $integrationData = $this->integration->data;
        list($fields, $submissionData) = $this->constructSubmissionData($this->submission->data);

        $submissionData['action'] = 'application.edited';
        $webhook = new Webhook($integrationData);
        $this->response = $webhook->request(null, null, $submissionData, [
            'headers'   => null,
            'json'      => false,
        ]);
    }

    protected function constructSubmissionData($data)
    {
        $sections = $this->application->sections()->with('fields')->get()->toArray();
        $fields = [];
        $submissionData = [];
        foreach ($sections as $section) {
            foreach ($section['fields'] as $field) {
                $fields[$field['id']] = $field['name'];
                if (isset($data[$field['name']])) {
                    $submissionData[$field['object']][$field['name']] = $data[$field['name']];
                }
            }
        }

        return [$fields, $submissionData];
    }
}
