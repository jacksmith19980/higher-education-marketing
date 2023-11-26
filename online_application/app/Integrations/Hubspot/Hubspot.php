<?php

namespace App\Integrations\Hubspot;

use Http;
use Crypt;
use App\Integrations\BasicIntegration;
use HubSpot\Factory as HubspotFactory;
use HubSpot\Client\Auth\OAuth\ApiException;
use App\Http\Controllers\Tenant\PDFController;
use HubSpot\Client\Crm\Contacts\Model\SimplePublicObjectInput;

class Hubspot extends BasicIntegration
{

    protected $hubspot;
    protected $accessToken;
    protected $name = 'Hubspot';
    public function __construct($settings, $decrypt = true)
    {

        if (!$settings['api_key']) {
            return false;
        }

        if ($decrypt) {
            $this->accessToken = Crypt::decrypt($settings['api_key']);
        } else {
            $this->accessToken = $settings['api_key'];
        }

        $this->hubspot =  HubspotFactory::createWithAccessToken($this->accessToken);

    }


    /**
     * Submit Application to HS  (Update HS Contact if Already exist)
     * @param  [type] $submission [description]
     * @param  [type] $student    [description]
     * @return [type]             [description]
     */
    public function submitApplication(
        $submission,
        $student,
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

        foreach (array_keys($submissionData) as $contactType) {
            // attach PDF URL to the contact
            if ($submission->status == 'Submitted') {

                $pdf = app(PDFController::class)->pdf($submission, 'email' , true);
                $submissionData[$contactType]['application_pdf'] = $pdf;
            }

            if ($stage) {
                $submissionData[$contactType]['lifecyclestage'] = $stage;
            }

            if (isset($submissionData[$contactType]['email'])) {
                // Create New Contact or update if existing
                $contact = $this->createOrUpdate($submissionData[$contactType]['email'], $submissionData[$contactType]);
                return $contact;
            }
            return null;
        }
    }

    public function createOrUpdate($email, $properties)
    {
        $props = $this->preparePropsForHS($properties);
        $contact = $this->createContact($email, $props);
    }

    /**
     * Create New HS Contact
     *
     * @param array $lead
     * @param null $contactType
     * @param string $stage
     * @param null $invoiceURL
     * @return array
     */
    public function createNewContact($lead, $contactType = null, $stage = null, $invoiceURL = null)
    {
        $props = $this->preparePropsForHS($lead);
        if ($stage) {
            $props[] =
                [
                    'property' => 'lifecyclestage',
                    'value' => $stage
                ];
        }
        $contact = $this->createContact($lead['email'], $props);
        return $contact;
    }

    protected function createContact($email, $props)
    {
        $url = "https://api.hubapi.com/contacts/v1/contact/createOrUpdate/email/$email";

        $contact = HTTP::withToken($this->accessToken)->withBody(json_encode(['properties' => array_filter($props)]), 'application/json')->post($url)->json();

        if (isset($contact['vid'])) {

            return $contact;

        } else {
            $props = $this->extractInvalidData($props, $contact['validationResults']);

            if (count(array_filter($props))) {
                $contact = $this->createContact($email, $props);
            }
        }
        return $contact;
    }
    protected function preparePropsForHS($properties)
    {
        $props = [];
        foreach ($properties as $key=>$value) {
            $props[] =
                [
                    'property' => $key,
                    'value' => $value
                ];
        }
        return $props;
    }

    protected function extractInvalidData($props, $validationResults)
    {
        $new = [];
        $inValid = array_column($validationResults, 'name');
        foreach ($props as $prop) {
            if (!in_array($prop['property'], $inValid)) {
                $new[] = $prop;
            }
        }
        return $new;
    }

    public function editContact($contact, $properties, $stage = null)
    {
        if ($stage) {
            $lead['lifecyclestage'] = $stage;
        }



        try {
            $input = new SimplePublicObjectInput(['properties' => $properties]);
            $apiResponse = $this->hubspot->crm()->contacts()->basicApi()->update($contact['vid'], $input);

        } catch (ApiException $e) {

            echo "Exception when calling basic_api->update: ", $e->getMessage();
        }
    }

    /**
     * Get Lists of LifeCycle Stages in HS
     *
     * @return array
     */
    public function getStages()
    {
        try {
            $list = [];
            $stages = $this->hubspot->crm()
            ->properties()
            ->coreApi()
            ->getByName("contacts", "lifecyclestage", false);
            if ($stages->getOptions()) {
                foreach ($stages->getOptions() as $option) {
                    $list['stages'][] =[
                        'id' => $option->getValue(),
                        'name' => $option->getLabel(),
                    ];
                }
            }
            return $list;
        } catch (ApiException $e) {
            echo "Exception when calling core_api->get_all: ", $e->getMessage();
        }
    }

    /**
     * Update Contact's LifeCycleStage
     *
     * @param [type] $contactId
     * @param integer $stageId
     * @return void
     */
    public function addToStage($contact, $stage)
    {
        // Check if the stage in HS
        $hsStages = $this->getStages();
        if(!in_array($stage , array_column($hsStages['stages'],'id' ))){
            return null;
        }

        $data['lifecyclestage'] = $stage;
        $response = $this->createOrUpdate($contact->email, $data);
        return $response;
    }

    /**
     * Update Contacts @TODO
     *
     * @param [type] $ids
     * @param [type] $data
     * @return void
     */
    public function updateContacts($ids, $data)
    {
        $BatchInputSimplePublicObjectBatchInput = new BatchInputSimplePublicObjectBatchInput(['inputs' => []]);
        try {
            $apiResponse = $client->crm()->contacts()->batchApi()->update($BatchInputSimplePublicObjectBatchInput);
            var_dump($apiResponse);
        } catch (ApiException $e) {
            echo "Exception when calling batch_api->update: ", $e->getMessage();
        }
    }
    public function getContact($email)
    {
        $url = "https://api.hubapi.com/contacts/v1/contact/email/$email/profile";
        try {
            $response = Http::withToken($this->accessToken)->get($url);
            return json_decode($response->body(), true);
        } catch (ApiException $e) {
            echo "Exception when calling batch_api->update: ", $e->getMessage();
        }
    }

    public function getContactAgencies($contactID)
    {
        return null;
    }

    public function addContactToAgency($contact, $agency)
    {
        return false;
    }

    public function addContactToAgencyByID($contactID, $agencyID)
    {
        return false;
    }

    public function addContactToAgencyByEmail($contact_email, $agency_email = null)
    {
        return false;
    }

    public function creatAgency($data)
    {
        if (empty($data)) {
            return false;
        }

        return $data;
    }

    public function editAgency($data, $agencyId)
    {
        if (empty($data)) {
            return false;
        }

        return $data;
    }

    public function addNote($note)
    {
        return false;
    }

    public function getField($alias = null)
    {
        return false;
    }

    public function getFieldList($alias = null)
    {
        return [];
    }

    public function getContactTypes()
    {
        return [
                'Lead'      => 'Lead',
                'Applicant' => 'Applicant',
                'Student'   => 'Student',
                'Agent'     => 'Agent',
            ];
    }

    public function getEmailsList($selectList = true)
    {
        return [];
    }

    public function getCustomFields($object = 'student')
    {
        $contact = $this->listContactCustomFields();
        $agency = $this->listAgencyCustomFields();
        $list = $contact + $agency;
        return $list;
    }

    protected function listContactCustomFields()
    {
        $list = [];
        try {
            $response = $this->hubspot->crm()->properties()->coreApi()->getAll("contacts", false);
            foreach ($response->getResults() as $prop) {
                $list[$prop->getName()] = $prop->getLabel();
            }
            return $list;
        } catch (ApiException $e) {
            return [];
        }
    }

    protected function listAgencyCustomFields()
    {
        $list = [];
        try {
            $response = $this->hubspot->crm()->properties()->coreApi()->getAll("companies", false);
            foreach ($response->getResults() as $prop) {
                $list[$prop->getName()] = $prop->getLabel();
            }
            return $list;
        } catch (ApiException $e) {
            return [];
        }
    }



    public function getAgency($id)
    {
        return $id;
    }

    public function getAgencies($filter = null)
    {
        return [];
    }

    public function createAgency($data)
    {
        return false;
    }

    public function updateAgency($id, $data)
    {
        return false;
    }

    public function sendEmail($emailId, $contact)
    {
        return false;
    }

    public function getRegisterationDefaults($settings , $data)
    {
        if(isset($settings['auth']['hubspot']['custom_properties']) &&
        count($settings['auth']['hubspot']['custom_properties'])){
            foreach ($settings['auth']['hubspot']['custom_properties'] as $key => $value) {
                if(!isset($data[$value['property']])){
                    $data[$value['property']] = $value['value'];
                }
            }
        }
        return $data;
    }
}
