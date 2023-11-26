<?php

namespace App\Integrations\Eversign;

use Eversign\ApiRequest;
use Eversign\Client;

class Eversign
{
    public $access_key;
    public $business_id;
    private const GET_DOCUMENT_TEMPLATE_ENDPOINT = 'https://api.eversign.com/api/document';

    public function __construct($access_key, $business_id)
    {
        $this->access_key = $access_key;
        $this->business_id = $business_id;
    }

    public function getDocumentOrTemplateFieldsIdentifiers($templateUrl)
    {
        $tmp = explode('/', $templateUrl);
        $document_hash = $tmp[count($tmp) - 1];

        $identifiers = [];
        $client = new Client($this->access_key, $this->business_id);
        $templates = $client->getTemplates();
        foreach ($templates as $template) {
            if ($template->getDocumentHash() == $document_hash) {
                foreach ($template->getFields()[0] as $field) {
                    $identifiers[] = $field->getIdentifier();
                }
            }
        }

        return $identifiers;
    }
}
