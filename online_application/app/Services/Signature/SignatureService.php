<?php

namespace App\Services\Signature;

use App\Services\Signature\SignatureInterface;
use App\Tenant\Models\Plugin;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use App\Tenant\Models\Envelope;
use App\Helpers\School\PluginsHelper;


class SignatureService
{
    protected $service;
    protected $serviceName;
    // protected $plugin;

    public function __construct()
    {
        $this->serviceName = $this->assignServiceName();
        if($this->serviceName){
            $service = 'App\\Services\\Signature\\' .ucwords($this->serviceName).'Service';
            $this->service = new $service;
        }

    }

    protected function assignServiceName()
    {
        if($serviceName = Setting::where('slug' , 'esignature_service_provider')->first()){
            return $serviceName->data;
        }
        $plugins = PluginsHelper::getPlugins('e-signature')->pluck('name')->toArray();
        if (empty($plugins)) {
            return null;
        }

        return $plugins[count($plugins) - 1];
    }

    public function getServiceName()
    {
        return $this->serviceName;
    }

    /**
     * Get A list of Templates
     *
     * @return array
     */
    public function getTemplatesList()
    {
        return $this->service->getTemplatesList();
    }

    /**
     * Get A list of Custom Fields
     *
     * @return array
     */
    public function getFields($templateID)
    {
        return $this->service->getFields($templateID);
    }

    /**
     * Send Document for Signature
     *
     * @return array
     */
    public function sendDocumentForSignature($data, $templateID, $esignAction, $submission)
    {
        return $this->service->sendDocumentForSignature($data, $templateID, $esignAction, $submission);
    }

    /**
     * Generate Document for Signature
     *
     * @return array
     */
    public function generateDocumentForSignature($data, $templateID, $esignAction, $submission)
    {
        return $this->service->generateDocumentForSignature($data, $templateID, $esignAction, $submission);
    }

    /**
     * Edit Created Document
     *
     * @return array
     */
    public function editDocument($data, $templateID, $esignAction, $submission, $contract)
    {
        return $this->service->editDocument($data, $templateID, $esignAction, $submission, $contract);
    }

    /**
     * Void a sent contract
     *
     * @return array
     */
    public function voidContract($contract)
    {
        return $this->service->voidContract($contract);
    }

    /**
     * Send Signature Reminder
     *
     * @return array
     */
    public function sendSignatureReminder($contract)
    {
        return $this->service->sendSignatureReminder($contract);
    }

    public function generateMultiTemplatesEnvelope($data, Student $student, Envelope $envelope, $submission = null , $signers = [])
    {
        return $this->service->generateMultiTemplatesEnvelope($data, $student, $envelope, $submission , $signers);
    }

    /**
     * Review Docusign Envelope and send it
     */
    public function reviewEnvelope($envelopeId, $studentId)
    {
        return $this->service->reviewEnvelope($envelopeId, $studentId);
    }

    public function envelopeDocumentsList($envelope)
    {
        return $this->service->envelopeDocumentsList($envelope);
    }

    /**
     * Get Download Link
     *
     * @return array
     */
    public function getDownloadLink($payload)
    {
        return $this->service->getDownloadLink($payload);
    }

    /**
     * Generate Document for Signature
     *
     * @return array
     */
    public function getEventDetails($request)
    {
        return $this->service->getEventDetails($request);
    }
}
