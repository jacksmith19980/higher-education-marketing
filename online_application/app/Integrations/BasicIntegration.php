<?php

namespace App\Integrations;
use App\Tenant\Models\Plugin;

class BasicIntegration
{
    protected $name = null;

    public function getIntegration(Plugin $plugin)
    {
        $integration = 'App\\Integrations\\' .ucwords($plugin->name).'\\' . ucwords($plugin->name) ;

        return new $integration($plugin->properties);

    }
    public function name()
    {
        return $this->name;
    }

    public function createNewContact($lead, $contactType, $stage = null, $invoiceURL = null)
    {
        return false;
    }

    public function getStages()
    {
        return [];
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
        return [];
    }

    public function getContact($email)
    {
        return $email;
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

    public function addToStage($contact, $stage)
    {
        return false;
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
    /**
     * Replace Application field Names with Mautic Field Name
     */
    protected function constructSubmissionData($submitedData, $mautic_field_pairs)
    {
        $data = [];
        foreach ($mautic_field_pairs as $pair) {

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

                    if (! is_array($submitedData[$pair['field']])) {
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

                }else{
                    $value = null;
                }
            }

            $data[$contact_type][$pair['mautic_field']] = $value;
        }

        return $data;
    }

    protected function extractFileListData($value)
    {
        return $value;
    }
}
