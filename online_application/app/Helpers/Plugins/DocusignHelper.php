<?php

namespace App\Helpers\Plugins;

use App\Tenant\Models\Contract;
use App\Tenant\Models\Envelope;
use App\Tenant\Models\Plugin;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use Auth;
use GuzzleHttp\Client;
use Illuminate\Support\Str;

class DocusignHelper
{
    public $plugin;
    public $client;
    public $name = 'docusign';

    public function __construct()
    {
        $this->plugin = Plugin::where('name', $this->name)->first();
        $this->client = new Client();
    }

    /**
     * Get Auth Token
     *
     * @return void
     */
    public function getToken()
    {
        $token = $this->plugin['properties']['access_token'];

        if ($this->plugin['properties']['expires_at'] > time()) {
            return $token;
        }

        return $this->authenticatePlugin($this->plugin['properties']['refresh_token'], 'refresh_token');
    }

    /**
     * Authenticate the plugin
     *
     * @param [type] $code
     * @param string $grantType
     * @return void
     */
    public function authenticatePlugin($code = null, $grantType = 'authorization_code')
    {
        if ($code) {
            $params = ['grant_type'=> $grantType];

            if ($grantType == 'authorization_code') {
                $params['code'] = $code;
            } else {
                $params['refresh_token'] = $code;
            }

            $response = $this->client->request('POST', env('DOCUSIGN_TOKEN_URL'),
                [
                    'headers' => [
                        'Authorization' => $this->getAuthHeader(),
                    ],
                    'form_params' => $params,
                ]);

            $body = $response->getBody();
            //$response = $body->getContents();
            $response = json_decode($body->read(2048), true);
            if (isset($response['access_token']) && isset($response['expires_in'])) {

                // Update Plugin Properties
                $properties = $this->plugin['properties'];

                $properties['access_token'] = $response['access_token'];
                $properties['refresh_token'] = $response['refresh_token'];

                $properties['expires_in'] = $response['expires_in'];

                $properties['expires_at'] = $this->getTokenExpireTimeStamp($response['expires_in']);

                unset($properties['_token']);
                $this->plugin['properties'] = $properties;

                if ($this->plugin->save()) {
                    echo 'Authenticated Successfully!';
                }
            }

            return $this->plugin->properties['access_token'];
        }
    }

    /**
     * Get Authorization Header
     *
     * @return void
     */
    protected function getAuthHeader()
    {
        $integrationKey = $this->plugin['properties']['client_id'];
        $secretKey = $this->plugin['properties']['secret_key'];

        return 'Basic '.base64_encode($integrationKey.':'.$secretKey);
    }

    /**
     * Check if the Token is expired
     *
     * @param [int] $expiresIn
     * @return bool
     */
    protected function getTokenExpireTimeStamp($expiresIn)
    {
        return time() + (int) $expiresIn;
    }

    /**
     * Get a list of Templates
     *
     * @return array
     */
    public function getTemplates()
    {
        $properties = $this->plugin['properties'];

        // return Empty List if Folder ID is missing
        if (! isset($properties['folder_id'])) {
            return [];
        }

        $query = [
            'folder_ids' => $properties['folder_id'],
        ];
        $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$properties['account_id'].'/templates';

        return $this->get($url, $query);
    }

    /**
     * Get Lits of document of a specific template
     *
     * @param [string] $templateId
     * @return array
     */
    public function getTemplateDocuments($templateId)
    {
        $properties = $this->plugin['properties'];
        $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$properties['account_id']."/templates/$templateId/documents";

        return $this->get($url);
    }

    public function getTemplateFields($templateId)
    {
        $properties = $this->plugin['properties'];

        // get the Documents
        $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$properties['account_id']."/templates/$templateId/documents";

        $documents = $this->get($url);
        $allFields = [];
        foreach ($documents['templateDocuments'] as $document) {
            $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$properties['account_id']."/templates/$templateId/documents/".$document['documentId'].'/tabs';

            $fields = $this->get($url);
            foreach ($fields as $type=>$fieldGroup) {
                if (isset($allFields[$type]) && count($allFields[$type])) {
                    $allFields[$type] = array_merge($allFields[$type], $fieldGroup);
                } else {
                    $allFields[$type] = $fieldGroup;
                }
            }
        }

        return $allFields;
    }

    /**
     * Send Document for Signature
     *
     * @param [type] $data
     * @param [type] $templateId
     * @param [type] $esignAction
     * @param [type] $student
     * @return array
     */
    public function sendDocumentForSignature($data, $templateId, $esignAction, $submission)
    {
        $properties = $this->plugin['properties'];
        try {
            // Create envelope definition
            $formParams = [
                'status'            =>'sent',
                'templateId'        => $templateId,
                'brandId'           => $properties['brand_id'],
                //'emailSubject'      =>'School Name - Registration Contract',
                'templateRoles'     => [
                    $this->getSchoolRoleDetails($esignAction),
                    $this->getStudentRoleDetails($data, $submission->student),
                ],
            ];

            $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$properties['account_id'].'/envelopes';

            $headers['Content-Type'] = 'application/json; charset=utf-8';
            $response = $this->post($url, $formParams, $headers, true);

            if (isset($response['envelopeId'])) {
                // Save Contract
                $contract = $this->saveContract($submission, $response);

                return $response;
            }
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    /**
     * Send Document for Signature
     *
     * @param [type] $data
     * @param [type] $templateId
     * @param [type] $esignAction
     * @param [type] $submission
     * @return array
     */
    public function generateDocumentForSignature($data, $templateId, $esignAction, $submission)
    {
        $properties = $this->plugin['properties'];

        try {
            // Create envelope definition
            $formParams = [
                'status'            =>'created',
                'templateId'        => $templateId,
                'brandId'           => $properties['brand_id'],
                //'emailSubject'      =>'School Name - Registration Contract',
                'templateRoles'     => [
                    $this->getSchoolRoleDetails($esignAction),
                    $this->getStudentRoleDetails($data, $submission->student),
                ],
            ];

            $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$properties['account_id'].'/envelopes';

            $headers['Content-Type'] = 'application/json; charset=utf-8';
            $response = $this->post($url, $formParams, $headers, true);

            if (isset($response['envelopeId'])) {

                // Create student's  Contract
                $contract = $this->saveContract($submission, $response);

                // Generate Embeded Signature
                $response = $this->generateEmbededSignature($response, $properties, $submission, $data);

                return $response;
            }
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    /* Edit Document for Signature
     *
     * @param [type] $data
     * @param [type] $templateId
     * @param [type] $esignAction
     * @param [type] $submission
     * @return array
     */
    public function editDocument($data, $templateId, $esignAction, $submission, $contract)
    {
        $properties = $this->plugin['properties'];
        try {
            if (isset($contract->uid)) {

                // Generate Embeded Signature
                $response = $this->generateEmbededSignature($contract->properties, $properties, $submission, $data);

                return $response;
            }
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    /* Void a Sent Contract
     *
     * @param [type] $data
     * @param [type] $templateId
     * @param [type] $esignAction
     * @param [type] $submission
     * @return array
     */
    public function voidContract($contract)
    {
        $properties = $this->plugin['properties'];
        try {
            if (isset($contract->uid)) {
                $properties = $this->plugin['properties'];

                $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$properties['account_id'].'/envelopes/'.$contract->properties['envelopeId'];

                $user = Auth::guard('web')->user();
                $formParams = [
                    'status'        => 'voided',
                    'envelopeId'    => $contract->properties['envelopeId'],
                    'voidedReason'  => 'Voided By: '.$user->name,
                ];
                $headers['Content-Type'] = 'application/json; charset=utf-8';
                $response = $this->put($url, $formParams, $headers);

                return $response;
            }
        } catch (\Exception $e) {
            //throw $th;
        }
    }

    public function sendSignatureReminder($contract)
    {
        $properties = $this->plugin['properties'];
        $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$properties['account_id'].'/envelopes/'.$contract->uid;
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $params = [
                    'resend_envelope' => true,
            ];
        $response = $this->put($url, $params, $headers);

        return $response;
    }

    public function getEventDetails($request)
    {
        $props = [
            'envelopeId'        => $request->envelopeId,
            'uri'               => $request->envelopeId,
            'status'            => $request->status,
            'statusDateTime'    => $request->statusChangedDateTime,
            'documents'         => isset($request['envelopeDocuments']) ? $this->getDocumentList($request['envelopeDocuments']) : [],
        ];

        switch ($request->status) {
            case 'completed':
                $props['signed_at'] = $request->sentDateTime;
                $props['name'] = $request->sender['userName'];
                break;

            case 'sent':
            case 'delivered':
                $props['sent_at'] = $request->sentDateTime;
                $props['name'] = $request->sender['userName'];
                break;
            default:
            break;
        }


        if ($request->status == 'completed') {
            $props['signed_at'] = $request->sentDateTime;
            $props['name'] = $request->sender['userName'];
        }

        $data = [
            'status'        => $request->status,
            'uid'           => $request->envelopeId,
            'service'       => $this->name,
            'properties'    => $props,
        ];

        return $data;
    }

    public function envelopeDocumentsList($envelope)
    {
        $envelopeId = $envelope->properties['envelopeId'];
        $properties = $this->plugin['properties'];
        $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$properties['account_id']."/envelopes/$envelopeId/documents";
        $query = [];

        if ($response = $this->get($url, $query, false)) {
            $documents = json_decode($response, true);

            return array_column($documents['envelopeDocuments'], 'name', 'documentId');
        }
    }

    public function getDownloadLink($payload)
    {
        $properties = $this->plugin['properties'];

        $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$properties['account_id'].'/envelopes/'.$payload['uid'].'/documents/'.$payload['documentId'];

        if ($payload['documentId'] == 'certificate') {
            $query['certificate'] = true;
        } elseif ($payload['documentId'] == 'combined') {
            $query = [];
        } else {
            $query['certificate'] = false;
        }

        $query['documents_by_userid '] = false;
        $query['recipient_id '] = false;

        if ($response = $this->get($url, $query, false)) {
            return response()->json(
                [
                    'status'        => 200,
                    'response'      => 'success',
                    'FileContent'   => base64_encode($response),
                    'ContentType'   => 'application/pdf',
                    'FileName'      => str_replace(' ', '_', $payload['documentName']).'.pdf',
                ]);
        }
    }

    protected function saveContract(Student $student, $response, Envelope $envelop, $submission = null)
    {
        $data = [
            'service'       => $this->name,
            'uid'           => $response['envelopeId'],
            'envelope_id'   => $envelop->id,
            'submission_id' => $submission,
            'title'         => $envelop->title,
            'status'        => $response['status'],
            'url'           => $response['uri'],
            'properties'    => $response,
            'student_id'    => $student->id,
            'user_id'       => Auth::guard('web')->user()->id,
        ];

        $contract = Contract::updateOrCreate(
            [
                'uid'           => $data['uid'],
            ],
            $data
        );

        return $contract;
    }

    /**
     * Generate Envelope with Multiple Docusign Templates
     *
     * @param array $data
     * @param Student $student
     * @param Envelope $envelope
     * @return void
     */
    public function generateMultiTemplatesEnvelope($data, Student $student, Envelope $envelope, $submission = null , $signers = [])
    {
        $properties['templates'] = json_decode($envelope->properties['templates'], true);

        $plugin = $this->plugin['properties'];

        try {
            $compositeTemplates = $this->generateCompositeTemplates($properties['templates'], $data, $plugin, $student);
            $formParams = [
               /*  'emailSubject'       => 'Contract',
                'emailBlurb'         => 'Contract', */
                'status'             => 'created',
                'brandId'            => $plugin['brand_id'],
                'compositeTemplates' => $compositeTemplates,


            ];

            $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$plugin['account_id'].'/envelopes';

            $headers['Content-Type'] = 'application/json; charset=utf-8';
            $response = $this->post($url, $formParams, $headers, true);
            if (isset($response['envelopeId'])) {
                $templatesDetaials = array_column($properties['templates'], 'name', 'id');

                $response['templates'] = $templatesDetaials;

                // Create student's  Contract
                $this->saveContract($student, $response, $envelope, $submission);

                $properties['redirect_url'] = url('/').'/students/'.$student->id;
                // Generate Embeded Signature
                $response = $this->generateEmbededSignature($response, $plugin, $properties);

                return $response;
            }
        } catch (\Exception $e) {
            //dd($e->getMessage());
        }
    }

    protected function generateCompositeTemplates($templates, $data, $plugin, $student)
    {
        //compositeTemplates
        $compositeTemplates = [];
        $i = 1;
        $c = 0;
        foreach ($templates as $key => $template) {
            $compositeTemplates[$c]['serverTemplates'][] =
            [
                                'sequence' => $i,
                                'templateId' => $template['id'],
            ];
            $compositeTemplates[$c]['inlineTemplates'][] =
            [
                                'sequence' => $i + 1,
                                'recipients' => [
                                    'signers' => [
                                        $this->getSchoolRoleDetails(null),
                                        $this->getStudentRoleDetails($data, $student),
                                    ],
                                    "notification" => [
                                    'useAccountDefaults'    => false,
                                        'reminders'          => [
                                            'reminderEnabled'       => true,
                                            'reminderDelay'         => 3,
                                            'reminderFrequency'     => 3,
                                        ]
                                    ]
                                ],
                                "envelope" => [
                                    "notification" => [
                                    'useAccountDefaults'    => false,
                                        'reminders'          => [
                                            'reminderEnabled'       => true,
                                            'reminderDelay'         => 3,
                                            'reminderFrequency'     => 3,
                                        ]
                                    ]
                                ]
            ];
            $i += 2;
            $c++;
        }

        return $compositeTemplates;
    }

    public function reviewEnvelope($envelopeId, $studentId)
    {
        $plugin = $this->plugin['properties'];
        $data = [
            'envelopeId'    => $envelopeId,
            'service'       => $this->name,
        ];
        $properties['redirect_url'] = url('/').'/students/'.$studentId;

        return $this->generateEmbededSignature($data, $plugin, $properties);
    }

    protected function generateEmbededSignature($data, $plugin, $properties)
    {
        $envelopeId = $data['envelopeId'];
        $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$plugin['account_id']."/envelopes/$envelopeId/views/edit";

        $receiptients = $this->getRecipients($envelopeId, $plugin);

        $data['service'] = $this->name;
        $params = [
            'assertionId'           => Str::random(8),
            'authenticationMethod'  => 'Email',
            'recipientId'           => 2,
            'returnUrl'             => isset($properties['redirect_url']) ? $properties['redirect_url'] : url('/'),
        ];

        //dd($params);

        foreach ($receiptients['signers'] as $signer) {
            if (strtolower($signer['roleName']) == 'student') {
                $params['clientUserId'] = $signer['userId'];
            }
            if (strtolower($signer['roleName']) == 'school') {
                $params['userId'] = $signer['userId'];
            }
        }
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $response = $this->post($url, $params, $headers, true);

        return $response['url'];
    }

    protected function getDocumentList($documents = null)
    {
        if (! $documents) {
            return null;
        }
        $list = [];
        foreach ($documents as $document) {
            $list[$document['documentId']] = [
                'id'                => $document['documentId'],
                'documentIdGuid'    => $document['documentIdGuid'],
                'name'              => $document['name'],
                'type'              => $document['type'],
            ];
        }

        return $list;
    }

    protected function getRecipients($envelopeId, $properties)
    {
        ///restapi/v2.1/accounts/{accountId}/envelopes/{envelopeId}/recipients
        $url = env('DOCUSIGN_BASE_URL').'/accounts/'.$properties['account_id']."/envelopes/$envelopeId/recipients";
        $response = $this->get($url);

        return $response;
    }

    protected function getSchoolRoleDetails($esignAction = null)
    {
        $user = Auth::guard('web')->user();
        if ($esignAction) {
            $prop = $esignAction->properties;

            return  [
                'name'    => isset($prop['schoolSignerName']) ? $prop['schoolSignerName'] : $user->email,
                'email'     => isset($prop['schoolSignerEmail']) ? $prop['schoolSignerEmail'] : $user->name,
                'roleName' => 'School',
            ];
        }

        return [
            'name'          => 'Felix Charland',
            'email'         => 'fcharland@cumberland.college',
            'roleName'      => 'School',
            'recipientId'   => 2,
        ];
    }

    protected function getStudentRoleDetails($data, $student)
    {
        $tabs = [];
        foreach ($data as $key=>$value) {
            $key = explode('|', $key);

            $tabs[$key[1]][] = [
                'tabLabel'  => $key[0],
                'value'     => is_array($value) ? implode(',', $value) : $value,
                'locked'    => true,
            ];
        }

        return  [
            'email'         => $student->email,
            'recipientId'   => 1,
            'roleName'      => 'Student',
            'name'          => $student->name,
            'tabs'          => $tabs,
        ];
    }

    protected function post($url, $formParams = null, $extraHeaders = null, $json = false)
    {
        $headers = [
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '.$this->getToken(),
        ];

        if ($extraHeaders) {
            $headers = $headers + $extraHeaders;
        }

        $params = [
            'headers' => $headers,
        ];

        if ($formParams) {
            if ($json) {
                $params['json'] = $formParams;
            } else {
                $params['form_params'] = $formParams;
            }
        }
        $response = $this->client->request('POST', $url, $params);
        $response = $response->getBody()->getContents();

        return json_decode($response, true);
    }

    /**
     * Guzzle Get Request
     *
     * @param [string] $url
     * @param [array] $query
     * @return array
     */
    protected function get($url, $query = null, $encoded = true)
    {
        $params = [
            'headers' => [
                'Accept'        => 'application/json',
                'Authorization' => 'Bearer '.$this->getToken(),
            ],
        ];

        if ($query) {
            $params['query'] = $query;
        }

        $response = $this->client->request('GET', $url, $params);
        $response = $response->getBody()->getContents();

        if (! $encoded) {
            return $response;
        }

        return json_decode($response, true);
    }

    protected function put($url, $body = null, $extraHeaders = null)
    {
        $headers = [
            'Accept'        => 'application/json',
            'Authorization' => 'Bearer '.$this->getToken(),
        ];

        if ($extraHeaders) {
            $headers = $headers + $extraHeaders;
        }

        $params = [
            'headers' => $headers,
        ];

        if ($body) {
            $params['body'] = json_encode($body);
        }

        $response = $this->client->request('PUT', $url, $params);

        $response = $response->getBody()->getContents();

        return json_decode($response, true);
    }
}
