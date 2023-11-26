<?php

namespace App\Services\Signature;

use App\Helpers\Plugins\DocusignHelper;
use App\Services\Signature\SignatureInterface;
use App\Tenant\Models\Envelope;

class DocusignService implements SignatureInterface
{
    protected $helper;

    public function __construct()
    {
        $this->helper = new DocusignHelper;
    }

    /**
     * Get List of templates
     */
    public function getTemplatesList()
    {
        $list = [];
        // get all templates
        $templatesList = $this->helper->getTemplates();
        if (isset($templatesList['envelopeTemplates'])) {
            foreach ($templatesList['envelopeTemplates'] as $template) {
                $list[$template['templateId']] = $template['name'];
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
        foreach ($templateFields as $type => $fields) {
            foreach ($fields as $field) {
                if (isset($field['tabId']) && $type != 'signHereTabs') {
                    $identifiers[$field['tabLabel'].'|'.$type] = $field['tabLabel'];
                }
            }
        }

        return $identifiers;
    }

    /**
     * sendDocumentforSignature
     */
    public function sendDocumentForSignature($data, $templateId, $esignAction, $submission)
    {
        $response = $this->helper->sendDocumentForSignature($data, $templateId, $esignAction, $submission);
        $response['service'] = $this->helper->name;

        return [
            $this->helper->name => $response,
        ];
    }

    /**
     * generateDocumentForSignature
     */
    public function generateDocumentForSignature($data, $templateId, $esignAction, $submission)
    {
        $response['url'] = $this->helper->generateDocumentForSignature($data, $templateId, $esignAction, $submission);
        $response['service'] = $this->helper->name;

        return [
            'esignature' => $response,
        ];
    }

    /**
     * editDocument
     */
    public function editDocument($data, $templateId, $esignAction, $submission, $contract)
    {
        $response['url'] = $this->helper->editDocument($data, $templateId, $esignAction, $submission, $contract);
        $response['service'] = $this->helper->name;

        return [
            'esignature' => $response,
        ];
    }

    /**
     * voidContract
     */
    public function voidContract($contract)
    {
        $response = $this->helper->voidContract($contract);
        $response['service'] = $this->helper->name;

        return [
            'esignature' => $response,
        ];
    }

    public function generateMultiTemplatesEnvelope($data, $student, Envelope $envelope, $submission = null , $signers = [])
    {
        $response['url'] = $this->helper->generateMultiTemplatesEnvelope($data, $student, $envelope, $submission , $signers);
        $response['service'] = $this->helper->name;

        return [
            'esignature' => $response,
        ];
    }

    public function reviewEnvelope($envelopeId, $studentId)
    {
        $response['url'] = $this->helper->reviewEnvelope($envelopeId, $studentId);
        $response['service'] = $this->helper->name;

        return [
            'esignature' => $response,
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
