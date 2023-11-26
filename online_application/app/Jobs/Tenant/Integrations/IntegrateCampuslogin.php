<?php

namespace App\Jobs\Tenant\Integrations;

use App\Http\Controllers\Tenant\PDFController;
use App\Integrations\Campuslogin\Campuslogin;
use App\Tenant\Models\Application;
use App\Tenant\Models\Integration;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\SerializesModels;

class IntegrateCampuslogin implements ShouldQueue
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

    }

    protected function shouldAbort()
    {
        // Check if submission is first Step
        if(isset($this->submission->data['step']) && $this->submission->data['step'] == 1)
        {
            return false;
        }
        // Check if submission is last Step
        if(isset($this->submission->data['step']) && (int) $this->submission->data['step'] == $this->application->sections->count())
        {
            return false;
        }
        return true;
    }

    public function submitApplication()
    {
        $integrationData = $this->integration->data;

        if(!isset($integrationData['sync_all_steps']) || !(bool) $integrationData['sync_all_steps']){
            // check if First or Last step
            if($this->shouldAbort()){
                return ;
            }
        }

        $this->fields = $this->getSubmissionFields($this->submission);

        $submissionData = $this->constructSubmissionData($this->submission->data , $integrationData['mautic_field_pairs']);

        $invoiceURL = null;

        if ($invoice = $this->student->invoices()->where('submission_id', $this->submission->id)->first()) {
            $invoiceURL = route('invoice.pay', [
                    'school' => request()->school,
                    'invoice' => $invoice
            ]);
        }

        $submissionData['invoice'] = $invoiceURL;
        $submissionData['action'] = 'application.submitted';
        $submissionData['status'] = $this->submission->status;
        $submissionData['properties'] = $this->submission->properties;
        $submissionData['uid'] = $this->submission->student->id;

        if ($this->submission->status == 'Submitted') {
            $pdf = app(PDFController::class)->pdf($this->submission, 'email');
            $submissionData['application_pdf'] = $pdf;
            // Attach Submission Date
            $submissionData['submission_date'] = date('Y-m-d h:i:s');
        }
        $campuslogin = new Campuslogin($integrationData);
        $this->response = $campuslogin->submitApplication($submissionData, $this->student, $integrationData, $this->application, $this->setting, $invoiceURL);
    }

    public function editApplication()
    {

    }

    protected function constructSubmissionData($submitedData, $mautic_field_pairs)
    {


        $data = [];
        foreach ($mautic_field_pairs as $pair) {
            $value = null;
            if (isset($pair['mautic_contact_type'])) {
                $contact_type = $pair['mautic_contact_type'];
            } else {
                $contact_type = 'Lead';
            }

            if (strpos($pair['field'], '|') == true) {
                $pair['field'] = explode("|", $pair['field']);
            }
            if(is_array($pair['field'])){

                if(isset($submitedData[$pair['field'][0]])) {
                    $mainFeild = isset($submitedData[$pair['field'][0]]) ? $submitedData[$pair['field'][0]] : [];
                    $value = isset($mainFeild[$pair['field'][1]]) ? $mainFeild[$pair['field'][1]] : null;
                }
            }else{

                if (isset($submitedData[$pair['field']])) {

                    if (!is_array($submitedData[$pair['field']])) {
                        if (strpos($submitedData[$pair['field']], '|') == false) {
                            $value = $submitedData[$pair['field']];
                        } else {
                            $value = explode('|', $submitedData[$pair['field']]);
                        }
                    } else {
                        $value = implode('|', $submitedData[$pair['field']]);
                    }
                    if ($this->fields[$pair['field']] == 'file_list') {
                        $value = $this->extractFileListData($value);
                    }

                }
            }
            $data[$contact_type][$pair['mautic_field']] = $value;
        }
        return $data;
    }

    protected function getSubmissionFields($submission)
    {
        $allFields = [];
        $submitedData = $submission->data;

        $fields = $submission->application->sections()->with('fields')->get()->pluck('fields')->toArray();

        if (count($fields)) {
            foreach ($fields as $group) {
                $group = array_column($group, 'field_type', 'name');
                $allFields += $group;
            }
        }

        return $allFields;
    }

    protected function extractFileListData($value)
    {
        return $value;
    }
}
