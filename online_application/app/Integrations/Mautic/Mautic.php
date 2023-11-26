<?php

namespace App\Integrations\Mautic;

use Mautic\MauticApi;
use Mautic\Auth\ApiAuth;
use App\Tenant\Models\Campus;
use Illuminate\Support\Facades\Crypt;
use App\Integrations\BasicIntegration;
use App\Http\Controllers\Tenant\PDFController;


class Mautic extends BasicIntegration
{
    public $auth;
    public $apiUrl;
    public $api;
    public $fields;
    protected $name = 'Mautic';
    public function __construct($settings, $decrypt = true)
    {
        if ($decrypt) {
            $password=Crypt::decrypt($settings['password']);
        } else {
            $password = $settings['password'];
        }


        $credentials = [
            'userName' => $settings['username'],
            'password' => $password,
        ];
        $initAuth = new ApiAuth();
        $this->auth = $initAuth->newAuth($credentials, 'BasicAuth');

        $this->apiUrl = isset($settings['base_url']) ? $settings['base_url'] : $settings['url'];
        $this->api = new MauticApi();
    }

    /**
     * Submit Application to Mautic (Update Mautic Contact if Already exist)
     * @param  [type] $submission [description]
     * @param  [type] $student    [description]
     * @return [type]             [description]
     */
    public function submitApplication(
        $submission,
        $student = null,
        $integrationData,
        $application,
        $setting,
        $invoiceURL = null
    ) {
        $submissionData = $submission->data;

        $stage = null;
        if (isset($setting['stages']) && ! empty($setting['stages'])) {
            $stage = $setting['stages']['application_init_stage'];
            if ($submission->status == 'Submitted' || $submission->steps_progress_status == '100') {
                $stage = $setting['stages']['application_submitted_stage'];
            }
        }
        if ($integrationData['custom_field_names']) {
            $this->fields = $this->getSubmissionFields($submission);
            $submissionData = $this->constructSubmissionData($submission->data, $integrationData['mautic_field_pairs']);
        }
        $contacts = [];
        $agencies = [];

        $submissionData = array_filter($submissionData);


        foreach (array_keys($submissionData) as $contactType) {
            if($contactType == 'Agency'){

                $agencyData = array_filter($submissionData[$contactType]);



                if(isset($agencyData['companyname'])){
                    $agency = $this->createAgency($agencyData);
                    $agencies[] = isset($agency['company']) ? reset($agency) : null;
                }

            }else{
                // attach PDF URL to the contact
                if ($submission->status == 'Submitted' || $submission->steps_progress_status == '100') {

                $pdf = app(PDFController::class)->pdf($submission, 'email');
                $submissionData[$contactType]['application_pdf'] = $pdf;

                // Attach Submission Date
                $submissionData[$contactType]['submission_date'] = date('Y-m-d h:i:s');
                }
                // Create new Mautic contact and submit application Data
                $contact = $this->createNewContact($submissionData[$contactType], $contactType, $stage, $invoiceURL);

                $contacts[] = isset($contact['contact']) ? reset($contact) : null;

            }
        }

        // Assign contatcs to agencies
        $this->addContactsToAgencies($contacts , $agencies);
        return $contacts;

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

    public function createNewContact($lead, $contactType, $stage = null, $invoiceURL = null)
    {
        if ($invoiceURL) {
            $lead['invoice'] = $invoiceURL;
        }
        $lead['last_active'] = date('Y-m-d h:i:s');
        $contact = $this->createContact($lead, $contactType, null);


        //mail('mattalah@higher-education-marketing.com','DEBUG CONTACT' , json_encode(['lead' => $lead ,'contact' => $contact,'contactType' => $contactType]));

        if (isset($contact['errors'])) {
            if ($contact['errors'][0]['code'] == 400) {
                $lead = $this->removeInvalidData($contact['errors'][0]['message'], $lead);
                // Try To Push again
                $contact = $this->createContact($lead);
            } else {
                $message = [
                        'response'  => $contact['errors'],
                        'message'   => $contact['error']['message'],
                        'leadData'  => $lead,
                    ];

                return $message;
            }
        }

        if (isset($contact['contact']['id']) && $stage) {
            $this->addToStage($contact['contact']['id'], $stage);
        } else {
            $details = [
                'contact' => $contact,
                'lead' => $lead,
                'contactType' => $contactType,
            ];
        }

        return $contact;
    }

    /**
     * Create Mautic Contact
     */
    public function createContact($data, $contactType = null, $action = null)
    {


        if (empty($data)) {
            return false;
        }

        if ($contactType) {
            $data['contact_type'] = $contactType;
        }

        $contactApi = $this->api->newApi('contacts', $this->auth, $this->apiUrl);

        return $contactApi->create($data);
    }

    public function editApplication($submission, $student, $integrationData, $fields = [])
    {
        $submissionData = $submission->data;
        // Find Mautic's Contact
        $email = isset($submissionData['email']) ? $submissionData['email'] : $student->email;

        $contact = $this->getContact($email);
        // No Contact
        if (!$contact['total'] || count($fields) == 0) {
            return null;
        }
        $contact = reset($contact['contacts']);
        $contactData = $contact['fields']['all'];

        if(isset($contact['stage']['id'])){
            $contactData['stage'] = $contact['stage']['id'];
        }
        $map = array_column($integrationData['mautic_field_pairs'], 'mautic_field', 'field');
        foreach ($fields as $field) {
            $contactData[$map[$field]] = $submissionData[$field];
        }

        $contact = $this->editContact($contactData, $contact['id']);
        return $contact;
    }

    protected function extractContactData($contact)
    {
        //dd($contact);
    }

    /**
     * Edit Contact
     *
     * @return void
     */
    public function editContact($data, $contactId)
    {
        $contactApi = $this->api->newApi('contacts', $this->auth, $this->apiUrl);

        return $contactApi->edit($contactId, $data, true);
    }

    /**
     * Remove Invalid Data from the lead feed based on the error message
     */
    protected function removeInvalidData($message, $data)
    {
        $message = preg_replace("/\r|\n/", '', $message);
        $message = str_replace('The response has unexpected status code (400).Response: ', '', $message);
        $message = json_decode($message, true);
        if (is_array($message)) {
            foreach ($message['errors'] as $error) {
                foreach ($error['details'] as $key => $value) {
                    unset($data[$key]);
                }
            }
        }

        return $data;
    }

    /**
     * Get List Of Mautic Emails
     *
     * @return array
     */
    public function getEmailsList($selectList = true)
    {
        $emailApi = $this->api->newApi('emails', $this->auth, $this->apiUrl);
        $emails = $emailApi->getList('', 0, 100, 'id', 'DESC', true, null);
        //dump($emails);
        if (!$selectList) {
            return $emails;
        }
        $list = [
            0 => 'Select Email',
        ];

        foreach ($emails['emails'] as $id => $email) {
            $list[$id] = $email['name'];
        }
        return $list;
    }

    public function sendEmail($emailId, $contact)
    {
        $emailApi = $this->api->newApi('emails', $this->auth, $this->apiUrl);

        // if we have Mautic Contact ID
        if (is_numeric($contact)) {
            $emailApi->sendToContact($emailId, $contact);
        }
        // If we have a list of recipient emails
        if (is_array($contact)) {
            foreach ($contact as $contactEmail) {
                $contacts = $this->getContact($contactEmail);
                $contactId = reset($contacts['contacts'])['id'];
                $response = $emailApi->sendToContact($emailId, $contactId);
            }
        }

        return true;
    }

    /**
     * Replace Application field Names with Mautic Field Name
     */
    /* protected function constructSubmissionData($submitedData, $mautic_field_pairs)
    {
        $data = [];
        foreach ($mautic_field_pairs as $pair) {
            if (isset($pair['mautic_contact_type'])) {
                $contact_type = $pair['mautic_contact_type'];
            } else {
                $contact_type = 'Lead';
            }

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
                if ($this->fields[$pair['field']] == 'file') {
                    $value = env('APP_URL') . '/submissions/applicants/files/view?fileName=' . $value;
                }
                $data[$contact_type][$pair['mautic_field']] = $value;
            }
        }
        return $data;
    } */

    protected function extractFileListData($value)
    {
        return $value;
    }

    /**
     * Get Mautic Custom Fields
     * @return [type] [description]
     */
    public function getCustomFields($object = 'student')
    {
        $contact = $this->listContactCustomFields();
        $agency = $this->listAgencyCustomFields();
        $list = $contact + $agency;
        return $list;
    }

    /**
     * List Mautic's custom fields
     *
     * @return void
     */
    protected function listContactCustomFields()
    {
        $list = [];
        try {
            $fieldApi = $this->api->newApi('contactFields', $this->auth, $this->apiUrl);
            $fields = $fieldApi->getList(null, 0, 1000, 'label')['fields'];
            if (count($fields)) {
                foreach ($fields as $field) {
                    $list['Contact'][$field['alias']] = $field['label'];
                }
                $list['Contact']['campus'] = 'Campus';
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }

        return $list;
    }

    protected function listAgencyCustomFields()
    {
        $list = [];
        try {
            $fieldApi = $this->api->newApi('companyFields', $this->auth, $this->apiUrl);
            $fields = $fieldApi->getList(null, 0, 1000, 'label')['fields'];
            if (count($fields)) {
                foreach ($fields as $field) {
                    $list['Agency'][$field['alias']] = $field['label'];
                }
            }
        } catch (\Exception $e) {
            //throw $th;
        }
        return $list;
    }

    /**
     * Edit Mautic Contact's Application Data
     * @param  [integer] $id   [Mautic Contact ID]
     * @param  [array] $data [Submited Data]
     * @return [Obj][Mautic contact]
     */
    protected function editStudentApplication($id, $data)
    {
        $contactApi = $this->api->newApi('contacts', $this->auth, $this->apiUrl);

        $contact = $contactApi->edit($id, $data, true);

        return $contact;
    }

    /**
     * Add Contact To Stage
     * @param [type] $contactId [description]
     */
    public function addToStage($contactId, $stageId = 1)
    {
        $stageApi = $this->api->newApi('stages', $this->auth, $this->apiUrl);

        return $stageApi->addContact($stageId, $contactId);
    }

    /**
     * Get Mautic Student Information By Email
     * @param  [Obj] $student  [Authorised Student Information]
     * @return [Obj][Mautic Contact]
     */
    public function getContact($email)
    {
        $contactApi = $this->api->newApi('contacts', $this->auth, $this->apiUrl);

        return $contactApi->getList('email:'.$email);
    }

    /**
     * Get List of Mautic Agencies
     * @return [type] [description]
     */
    public function getAgency($id)
    {
        // Get contact field context:
        $companyApi = $this->api->newApi('companies', $this->auth, $this->apiUrl);

        return $companyApi->get($id);
    }

    /**
     * Get List of Mautic Agencies
     * @return [type] [description]
     */
    public function getAgencies($filter = null)
    {
        // Get contact field context:
        $companyApi = $this->api->newApi('companies', $this->auth, $this->apiUrl);

        return $companies = $companyApi->getList($filter, 0, 1000, 'id', null, false, true);
    }

    /**
     * Create Mautic Agency
     * @return [type] [description]
     */
    public function createAgency($data)
    {
        $companyApi = $this->api->newApi('companies', $this->auth, $this->apiUrl);

        return $companyApi->create($data);
    }

    /**
     * Edit Mautic Agency
     * @return [type] [description]
     */
    public function updateAgency($id, $data)
    {
        $companyApi = $this->api->newApi('companies', $this->auth, $this->apiUrl);

        return $companyApi->edit($id, $data, true);
    }

    /**
     * Get Mautic Stages
     */
    public function getStages()
    {
        $stageApi = $this->api->newApi('stages', $this->auth, $this->apiUrl);
        return $stageApi->getList('', 0, 100, '', '', true, '');
    }

    /**
     * Get Mautic Stages
     */
    public function getContactTypes()
    {
        $fieldApi = $this->api->newApi('contactFields', $this->auth, $this->apiUrl);

        $fields = $fieldApi->getList('', 0, 100, '', '', true, '');
    }

    public function getContactAgencies($contactID)
    {
        $contactApi = $this->api->newApi('contacts', $this->auth, $this->apiUrl);

        return $contactApi->getContactCompanies($contactID);
    }

    public function addContactsToAgencies($contacts = [], $agencies= [])
    {
        if(count(array_filter($contacts)) && count(array_filter($agencies))){
            foreach ($contacts as $contact) {
                $this->addContactToAgency($contact['id'] , $agencies[0]['id']);
            }
        }
    }


    public function addContactToAgency($contact, $agency)
    {
        if (! is_numeric($contact)) {
            // Get Contact ID
            if ($mauticContact = $this->getContact($contact)) {
                $contactID = key($mauticContact['contacts']);
            }
        } else {
            $contactID = $contact;
        }

        if (is_numeric($agency)) {
            // ADD Contact to agency by contactID and AgencyID
            $this->addContactToAgencyByID($contactID, $agency);

            return true;
        } else {
            $this->addContactToAgencyByEmail($contact, $agency);

            return true;
        }
    }

    public function addContactToAgencyByID($contactID, $agencyID)
    {
        if (! $contactID || ! $agencyID) {
            return false;
        }
        $companyApi = $this->api->newApi('companies', $this->auth, $this->apiUrl);

        return $companyApi->addContact($agencyID, $contactID);
    }

    public function addContactToAgencyByEmail($contact_email, $agency_email = null)
    {
        if (! $agency_email || ! $contact_email) {
            return false;
        }

        $contact = $this->getContact($contact_email);

        if (isset($contact['contacts'])) {
            $contact = reset($contact['contacts']);
        }

        $agencies = $this->getAgencies($agency_email);
        if (isset($agencies['companies'])) {
            foreach ($agencies['companies'] as $agency) {
                $companyApi = $this->api->newApi('companies', $this->auth, $this->apiUrl);
                // Add Contact To agency
                return $companyApi->addContact($agency['id'], $contact['id']);
            }
        }
    }

    /**
     * Create Mautic Agency Mautic Agencies
     * @return [type] [description]
     */
    public function creatAgency($data)
    {
        if (empty($data)) {
            return false;
        }
        $companyApi = $this->api->newApi('companies', $this->auth, $this->apiUrl);

        return $company = $companyApi->create($data);
    }

    public function editAgency($data, $agencyId)
    {
        if (empty($data)) {
            return false;
        }
        $companyApi = $this->api->newApi('companies', $this->auth, $this->apiUrl);

        return $companyApi->edit($agencyId, $data, true);
    }

    /**
     * Add Mautic Note
     *
     * @param array $note
     * @return void
     */
    public function addNote($note)
    {
        if (empty($note)) {
            return false;
        }
        $noteApi = $this->api->newApi('notes', $this->auth, $this->apiUrl);

        return $noteApi->create($note);
    }

    public function getField($alias = null)
    {
        if (! $alias) {
            return false;
        }

        $fieldApi = $this->api->newApi('contactFields', $this->auth, $this->apiUrl);
        $fields = $fieldApi->getList('', 0, 200, 'id', 'DESC', true, null);

        if (! isset($fields['total']) || ! $fields['total']) {
            return false;
        }
        foreach ($fields['fields'] as $id => $field) {
            if ($field['alias'] == $alias) {
                return $field;
            }
        }
    }

    public function getFieldList($alias = null)
    {
        $data = [];
        if (! $alias) {
            return false;
        }
        if (! $field = $this->getField($alias)) {
            return [];
        }

        if (is_array($field['properties']) && array_key_exists('list', $field['properties'])) {
            foreach ($field['properties']['list'] as $key => $option) {
                $data[$option['value']] = $option['label'];
            }
            $data['campus'] = 'Campus';

            return $data;
        } else {
            return [];
        }
    }

     public function getRegisterationDefaults($settings , $data)
    {

        $defaults = [
            'channel' => isset($settings['auth']['mautic_channel']) ? $settings['auth']['mautic_channel'] : null,

            'request_type' => isset($data['request_type']) ? $data['request_type'] : (isset($settings['auth']['mautic_request_type']) ? $settings['auth']['mautic_request_type'] : null),
        ];

        if (isset($settings['auth']['mautic_default_campus']) && ! isset($data['campus'])) {
            $defaults['campus'] = $settings['auth']['mautic_default_campus'];

        }elseif(isset($data['campus'])) {

            if($campus = Campus::where('id',$data['campus'])->orWhere('slug' , $data['campus'])->first()) {

                $defaults['campus'] = $campus->slug;
            }
        }

        if (isset($settings['auth']['mautic_default_source']) && ! isset($data['utm_source'])) {
            $defaults['utm_source'] = $settings['auth']['mautic_default_source'];
        }

        return array_filter(array_merge($data, $defaults));

    }
}
