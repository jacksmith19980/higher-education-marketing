<?php

namespace App\Services\Signature;

use App\Tenant\Models\Envelope;
use App\Tenant\Models\Student;

interface SignatureInterface
{
    public function getTemplatesList();

    public function getFields($templateId);

    public function sendDocumentForSignature($data, $templateId, $esignAction, $submission);

    public function generateDocumentForSignature($data, $templateId, $esignAction, $submission);

    public function generateMultiTemplatesEnvelope($data, Student $student, Envelope $envelope, $submission = null);

    public function reviewEnvelope($envelopeId, $studentId);

    public function editDocument($data, $templateId, $esignAction, $submission, $contract);

    public function voidContract($contract);

    public function getEventDetails($request);

    public function envelopeDocumentsList($envelopeId);
}
