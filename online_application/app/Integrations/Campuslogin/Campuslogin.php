<?php

namespace App\Integrations\Campuslogin;

use Http;
use App\Integrations\BasicIntegration;

class Campuslogin extends BasicIntegration
{

    protected $basicUrl;
    protected $ORGID;
    protected $MailListID;
    protected $name = 'CampusLogin';

    public function __construct($settings, $decrypt = true)
    {
        $this->basicUrl = $settings['url'];
        $this->ORGID = $settings['ORGID'];
        $this->MailListID = $settings['MailListID'];

    }




    /**
     * Submit Application to HS  (Update HS Contact if Already exist)
     * @param  [type] $submission [description]
     * @param  [type] $student    [description]
     * @return [type]             [description]
     */
    public function submitApplication(
        $submissionData,
        $student,
        $integrationData,
        $application,
        $setting,
        $invoiceURL = null
    ) {
        $data = $submissionData['Lead'];
        $data['invoiceURL'] = $invoiceURL;
        $data += $this->getDefaults();
        $reposnse = $this->createContact($data);
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
    public function createNewContact($contact, $contactType = null, $stage = null, $invoiceURL = null)
    {
        $data['FirstName'] = $contact['firstname'];
        $data['Lastname'] = $contact['lastname'];
        $data['Email'] = $contact['email'];
        $data['Telephone'] = isset( $contact['phone']) ?  $contact['phone'] : null;
        $data['campusID'] = isset( $contact['campusID']) ?  $contact['campusID'] : null;
        $data['mediaID'] = isset( $contact['mediaID']) ?  $contact['mediaID'] : null;
        $data['programID'] = isset( $contact['programID']) ?  $contact['programID'] : null;
        $data = array_filter($data);
        $data += $this->getDefaults();
        return $this->createContact($data);
    }

    protected function createContact($data)
    {
        $data = http_build_query(array_filter($data));
        $url = $this->basicUrl."?".$data;
        $response = HTTP::asForm()
        ->post($url , []);
        $response->onError(function(){

        });
        return $response;
    }

    protected function getDefaults()
    {
        return [
            "ImmediatelyReturn" => false,
            "nvc"           => "Y",
            "IsTest"        => "N",
            "ORGID"         => $this->ORGID,
            "MailListID"    => $this->MailListID
        ];
    }

    public function editContact($contact, $properties, $stage = null)
    {

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
        switch ($object) {
            case 'student':
                return $this->listContactCustomFields();
                break;

            case 'agency':
                return $this->listAgencyCustomFields();
                break;

            default:
                return [];
                break;
        }
    }

    protected function listContactCustomFields()
    {
        $list = [];
        return $list;
    }

    protected function listAgencyCustomFields()
    {
        $list = [];
        return $list;

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
        $defaults = [
            'campusID' => (isset($data['campus'])) ? $data['campus'] : ((isset($settings['auth']['campuslogin']['campusID'])) ? $settings['auth']['campuslogin']['campusID'] : null),
            'mediaID' => (isset($data['mediaid'])) ? $data['mediaid'] : ((isset($settings['auth']['campuslogin']['mediaID'])) ? $settings['auth']['campuslogin']['mediaID'] : null),
            'programID' => (isset($data['program'])) ? $data['program'] : null,
        ];
        return array_filter(array_merge($data, $defaults));
    }
}
