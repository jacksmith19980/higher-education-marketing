<?php

namespace App\Services\Signature;

use App;
use App\School;
use App\Services\Signature\EverSignTemplate;
use App\Tenant\Models\Plugin;
use Auth;
use Eversign\ApiRequest;
use Eversign\Client;
use Eversign\DocumentTemplate;
use Eversign\Field;
use Eversign\Signer;
use GuzzleHttp\Client as Http;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class EversignService
{
    protected $credentials;

    // Get eversign Credentials
    protected function credentials()
    {
        $plugin = Plugin::where('name', 'eversign')->first();

        return $this->credentials = $plugin->properties;
    }

    protected function client()
    {
        $credentials = $this->credentials();
        $this->client = new Client($credentials['access_key'], $credentials['business_id']);

        return $this->client;
    }

    /**
     * Get List of templates
     */
    public function getTemplatesList()
    {
        $templates = $this->client()->getTemplates();
        $list = [];
        foreach ($templates as $template) {
            $list[$template->getDocumentHash()] = $template->getTitle();
        }

        return $list;
    }

    /**
     * Get list if Fields
     */
    public function getFields($document_hash)
    {
        $templates = $this->client()->getTemplates();
        $identifiers = [];
        foreach ($templates as $template) {
            if ($template->getDocumentHash() == $document_hash) {
                foreach ($template->getFields()[0] as $field) {
                    $identifiers[] = $field->getIdentifier();
                }
            }
        }

        return $identifiers;
    }

    /**
     * Create EverSign Document and return the iframe link
     */
    public function createDocument($properties, $fields, $renew = false)
    {
        $documentTemplate = new EversignTemplate();
        $documentTemplate->setTemplateId($properties['documentHash']);
        $documentTemplate->setMessage($properties['documentMessage']);
        $documentTemplate->setEmbeddedSigningEnabled(true);
        $signer = new Signer();
        $signer->setRole('Client');
        // get locale
        $lang = App::getLocale();
        if ($user = Auth::guard('student')->user()) {
            $signer->setName($user->name);
            $signer->setEmail($user->email);
            $signer->setLanguage($lang);
            $documentTemplate->setTitle($properties['documentTitle'].'-'.$user->name);
        }
        $documentTemplate->appendSigner($signer);

        if (isset($properties['enableSchoolSignature'])) {
            $school = new Signer();
            $school->setRole('School');
            $school->setName($properties['schoolSignerName']);
            $school->setEmail($properties['schoolSignerEmail']);
            $signer->setLanguage($lang);
            $signer->setDeliverEmail(true);
            $documentTemplate->appendSigner($school);
        }

        foreach ($fields as $key => $value) {
            $field = new Field();
            $field->setIdentifier($key);
            $field->setValue($value);
            $documentTemplate->appendField($field);
        }

        $doc = $this->client()->createDocumentFromTemplate($documentTemplate);

        return [
            'documentHash' => $doc->getDocumentHash(),
            'documentURL' => $doc->getSigners()[0]->getEmbeddedSigningUrl(),
        ];
    }

    public function downloadLink($documentHash)
    {
        $credentials = $this->credentials();
        $url = 'https://api.eversign.com/api/download_final_document?access_key='.$credentials['access_key'].'&business_id='.$credentials['business_id'].'&document_hash='.$documentHash.'&audit_trail=1&url_only=1';
        $client = new Http();
        //GuzzleHttp\Client
        $result = $client->get($url);
        $body = $result->getBody();
        $response = $body->getContents();
        $response = json_decode($response, true);

        return $response;

        /*         if($response['success']){
                    return 'https://'.$response['url'];
                } */
    }

    public function updateDocument($properties, $fields, $documentHash)
    {
        $document = $this->client()->getDocumentByHash($documentHash);
        // Delete the Documnet
        try {
            /*  if($cancelled = $this->client->cancelDocument($document)){
                return $this->createDocument( $properties , $fields , true);
            } */

            return $this->createDocument($properties, $fields, true);
        } catch (Exception $e) {
            dump($e);
        }
        // Create New Docuement
        //return $this->createDocument( $properties , $fields);
    }
}
