<?php

namespace App\Services\Signature;


use App\Tenant\Models\Envelope;
use App\Tenant\Models\Submission;
use App\Helpers\Plugins\PandadocHelper;
use App\Services\Signature\SignatureInterface;


class PandadocService implements SignatureInterface
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new PandadocHelper;
    }

    /**
     * Get List of templates
     */
    public function getTemplatesList()
    {
        $list = [];
        // get all templates
        $templatesList = $this->helper->getTemplates();
        if(isset($templatesList['results']) && count($templatesList['results'])){
            foreach ($templatesList['results'] as $template){
                $list[$template['id']] = $template['name'];
            }
        }
        return $list;
    }

    /**
     * Get list if Fields
     */
    public function getFields($templateId)
    {
        if(!$template = $this->helper->getTemplateFields($templateId)){
            return [];
        }
        $identifiers = [];
            if(isset($template['fields'])){
                foreach ($template['fields'] as $field) {
                    if(isset($field['type'])){
                        if($field['type'] == 'signature'){
                        }elseif($field['type'] != 'signature' && isset($field['merge_field'])){
                            $identifiers[$field['merge_field'] . "|" . $field['type']] = $field['name'];
                        }
                    }
                }
            }

            if(isset($template['tokens'])){
                foreach ($template['tokens'] as $token) {
                    $identifiers[$token['name'] . "|token"] = $token['name'];
                }
            }
        return $identifiers;
    }

    /**
     * sendDocumentforSignature
     */
    public function sendDocumentForSignature($data, $templateId , $esignAction , $submission)
    {
        $response =  $this->helper->sendDocumentForSignature($data , $templateId , $esignAction , $submission);
        $response['service'] = $this->helper->name;
        return [
            'esignature' => $response
        ];

    }

    /**
     * generateDocumentForSignature
     */
    public function generateDocumentForSignature($data, $templateId , $esignAction , $submission)
    {
        $response['url'] =  $this->helper->generateDocumentForSignature($data , $templateId , $esignAction , $submission);
        $response['service'] = $this->helper->name;
        return [
            'esignature' => $response
        ];
    }

    /**
     * editDocument
     */
    public function editDocument($data, $templateId , $esignAction , $submission , $contract)
    {
        $response['url'] =  $this->helper->editDocument($data , $templateId , $esignAction , $submission,$contract);
        $response['service'] = $this->helper->name;
        return [
            'esignature' => $response
        ];
    }

    /**
     * voidContract
     */
    public function voidContract($contract)
    {
        $response =  $this->helper->voidContract($contract);
        $response['service'] = $this->helper->name;

        return [
            'esignature' => $response
        ];
    }

    public function generateMultiTemplatesEnvelope($data , $student ,Envelope $envelope , $submissionId = null , $signers = [])
    {
        $submission = Submission::find($submissionId);
        $response['documents'] =  $this->helper->generateMultiTemplatesEnvelope($data , $student, $envelope , $submission , $signers);

        $response['service'] = $this->helper->name;

        return [
            'esignature' => $response
        ];
    }

    public function reviewEnvelope($envelopeId , $studentId)
    {

        $response['url'] =  $this->helper->reviewEnvelope($envelopeId , $studentId);
        $response['service'] = $this->helper->name;

        return [
            'esignature' => $response
        ];
    }


    /**
     * Extract Event Details forom Webhook
     *
     * @param [type] $request
     * @return void
     */
    public function getEventDetails($request)
    {
        return $this->helper->getEventDetails($request);
    }

    /**
     * Send Signature Reminder
     *
     * @param [type] $request
     * @return void
     */
    public function sendSignatureReminder($contract)
    {
        return $this->helper->sendSignatureReminder($contract);
    }

    /**Get list of documents in an Envelope */
    public function envelopeDocumentsList($envelopeId)
    {
        return $this->helper->envelopeDocumentsList($envelopeId);

    }

    /**
     * Get Document Download Link
     *
     * @param [array] $payload
     * @return void
     */
    public function getDownloadLink($payload)
    {
        return $this->helper->getDownloadLink($payload);

    }


}
