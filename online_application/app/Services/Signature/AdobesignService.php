<?php

namespace App\Services\Signature;


use App\Tenant\Models\Envelope;
use App\Helpers\Plugins\AdobesignHelper;
use App\Services\Signature\SignatureInterface;


class AdobesignService implements SignatureInterface
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new AdobesignHelper;
    }

    /**
     * Get List of templates
     */
    public function getTemplatesList()
    {
        $list = [];
        // get all templates
        $templatesList = $this->helper->getTemplates();

        if(isset($templatesList['libraryDocumentList'])){
            foreach ($templatesList['libraryDocumentList'] as $template){
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
        $templateFields = $this->helper->getTemplateFields($templateId);
        $identifiers = [];
        if($templateFields){
            foreach ($templateFields as $field) {
                if(isset($field['inputType'])){
                    if($field['inputType'] == 'SIGNATURE'){
                        $fieldName = str_replace(' ', "_", $field['name']);
                        $identifiers[ $fieldName. "_first_name|" . $field['inputType']] = $fieldName . "_first_name";

                        $identifiers[$fieldName . "_last_name|" . $field['inputType']] = $fieldName . "_last_name";

                        $identifiers[$fieldName . "_email|" . $field['inputType']] = $fieldName . "_email";

                    }else{
                        $identifiers[$field['name'] . "|" . $field['inputType']] = $field['name'];
                    }
                }
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


    public function generateMultiTemplatesEnvelope($data , $student ,Envelope $envelope , $submission = null , $signers = [])
    {
        $response['url'] =  $this->helper->generateMultiTemplatesEnvelope($data , $student, $envelope , $submission , $signers);
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
