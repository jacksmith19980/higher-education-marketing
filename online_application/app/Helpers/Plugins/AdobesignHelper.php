<?php
namespace App\Helpers\Plugins;

use Str;
use Auth;
use Crypt;
use App\User;
use Carbon\Carbon;
use GuzzleHttp\Client;
use App\Tenant\Models\Plugin;
use App\Tenant\Models\Student;
use App\Tenant\Models\Contract;
use App\Tenant\Models\Envelope;
use App\Tenant\Models\Submission;


class AdobesignHelper
{
    public $plugin;
    public $client;
    public $name = 'adobesign';

    public function __construct()
    {
        $this->plugin = Plugin::where('name', $this->name)->first();
        $this->client =  new Client();

        if(isset($this->plugin['properties']['refresh_token'])){
            $this->authenticatePlugin($this->plugin['properties']['refresh_token'], 'refresh_token');
        }
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
    public function authenticatePlugin($code, $grantType = 'authorization_code')
    {
        $properties = $this->plugin->properties;

        if ($code) {

            $params = [
                'grant_type'    => $grantType,
                'client_id'     => $properties['client_id'],
                'client_secret' => Crypt::decrypt($properties['client_secret']),
                'redirect_uri'  => $properties['redirect_uri'],
                'state'         => Str::random(64),
            ];

            if ($grantType == 'authorization_code') {
                $params['code'] =  $code;
                $url = env('ADOBESIGN_TOKEN_URL').  "/oauth/v2/token";
            } else {
                $params['refresh_token'] =  $code;
                $url = env('ADOBESIGN_TOKEN_URL') . "/oauth/v2/refresh";
            }

            try {
                $response = $this->client->request(
                    'POST',
                    $url,
                    [
                        'form_params' => $params
                    ]
                );

                $body = $response->getBody();
                $response = json_decode($body->read(2048), true);
                if (isset($response['access_token']) && isset($response['expires_in'])) {
                    // Update Plugin Properties
                    $properties = $this->plugin['properties'];

                    $properties['access_token'] = $response['access_token'];
                    $properties['expires_in'] = $response['expires_in'];

                    $properties['expires_at'] = $this->getTokenExpireTimeStamp($response['expires_in']);

                        if (isset($response['refresh_token'])) {
                            $properties['refresh_token'] = $response['refresh_token'];
                        }
                        if (isset($response['api_access_point'])) {
                            $properties['api_access_point'] = $response['api_access_point'];
                        }
                        if (isset($response['web_access_point'])) {
                            $properties['web_access_point'] = $response['web_access_point'];
                        }


                        unset($properties['_token']);
                        $this->plugin['properties'] = $properties;

                        if ($this->plugin->save() && $grantType == 'authorization_code') {
                            echo "Authenticated Successfully!";
                        }
                }
                return $this->plugin->properties['access_token'];

            } catch (\Exception $e) {

            }

        }
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
        if (!isset($properties['group_id'])) {
            return [];
        }
        $query = [
            'pageSize' => 100,
            'groupId'  => $properties['group_id']
        ];


        $url =  $properties['api_access_point'] . env('ADOBESIGN_BASE_URL') . "/libraryDocuments";

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
        $url = env('ADOBESIGN_BASE_URL') . "/accounts/".$properties['account_id']."/templates/$templateId/documents";
        return $this->get($url);
    }

    public function getTemplateFields($templateId)
    {
        $properties = $this->plugin['properties'];

        ///libraryDocuments/{libraryDocumentId}/formFields
        // get the Documents
        $url = $properties['api_access_point'] . env('ADOBESIGN_BASE_URL') . "/libraryDocuments/$templateId/formFields";
        $fields = $this->get($url);

        if (count($fields['fields'])) {
            return $fields['fields'];
        }
        return null;
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
                    $this->getStudentRoleDetails($data, $submission->student)
                ]
            ];

            $url = env('ADOBESIGN_BASE_URL') ."/accounts/".$properties['account_id']."/envelopes";

            $headers['Content-Type'] = 'application/json; charset=utf-8';
            $response = $this->post($url, $formParams, $headers, true);

            if (isset($response['envelopeId'])) {
                // Save Contract
                $contract = $this->saveContract($submission->student, $response, $envelope, $submission);

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
                    $this->getStudentRoleDetails($data, $submission->student)
                ]
            ];

            $url = env('ADOBESIGN_BASE_URL') ."/accounts/".$properties['account_id']."/envelopes";

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
        try {
            if (isset($contract->uid)) {
                $properties = $this->plugin['properties'];
                $url = $properties['api_access_point'] . env('ADOBESIGN_BASE_URL') . "/agreements/$contract->uid/state";
                $user = Auth::guard('web')->user();
                $params = [
                    "state"                     => "CANCELLED",
                    "agreementCancellationInfo" => [
                        'comment'           => "Voided By: $user->name - at: " . Carbon::now()->format('Y-m-d H:i:s'),
                        "notifyOthers"      => true
                    ]
                ];
                $headers['Content-Type'] = 'application/json; charset=utf-8';
                $this->put($url, $params, $headers);
                $response['envelopeId'] = $contract->uid;
                return $response;
            }
        } catch (\Exception $e) {
            dd($e->getMessage());
        }
    }


    public function sendSignatureReminder($contract)
    {
        $properties = $this->plugin['properties'];
        $url = env('ADOBESIGN_BASE_URL') ."/accounts/".$properties['account_id']."/envelopes/". $contract->uid;
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $params = [
                    "resend_envelope" => true
            ];
        $response = $this->put($url, $params, $headers);
        return $response;
    }

    public function getAgreementStatus($id)
    {
        $plugin = $this->plugin['properties'];
        $url = $plugin['api_access_point'] . env('ADOBESIGN_BASE_URL') . "/agreements/$id/events";

        $headers['Content-Type'] = 'application/json; charset=utf-8';

        $response =  $this->get($url, [], $headers, true);
        if (isset($response['events'])) {
            return $response['events'];
        }

        return null;
    }

    public function getEventDetails($request)
    {
        if ($request->header('x-adobesign-clientid') == null) {
            abort(419);
        }

        $payload = $request->agreement;

        if ($payload['id']) {
            $agreement = Contract::where('uid', $payload['id'])->with('user', 'student')->first();
            $user = User::where('id', $agreement->user_id)->first();
        }
        // Get Events Trail
        $events = $this->getAgreementStatus($agreement->uid);
        // Get Last Event
        $lastEvent = $events[ count($events) - 1 ];

        $props = [
            'envelopeId'        => $agreement->uid,
            'uri'               => '',
            'statusDateTime'    => Carbon::now()->format('Y-m-d h:i:s'),
            'documents'         => isset($payload['documentsInfo']) ? $this->getDocumentList($payload['documentsInfo']) : [],
        ];

        switch ($lastEvent['type']) {

            case 'CREATED':
                $props['status'] = 'created';
                $props['created_at'] = Carbon::now()->format('Y-m-d h:i:s');
                $props['name'] = $user->name;
                break;

            case 'ACTION_REQUESTED':
                // Check if action by Student
                if (!$this->actionByStudent($lastEvent, $agreement->student)) {
                    $props['status'] = 'sent';
                    $props['signed_at'] = Carbon::now()->format('Y-m-d h:i:s');
                    $props['name'] = $user->name;
                } else {
                    return [];
                }
                break;

            case 'EMAIL_VIEWED':
                // Check if action by Student
                if ($this->actionByStudent($lastEvent, $agreement->student)) {
                    $props['status'] = 'delivered';
                    $props['signed_at'] = Carbon::now()->format('Y-m-d h:i:s');
                    $props['name'] = $agreement->student->name;
                } else {
                    return [];
                }
                break;

            case 'ACTION_COMPLETED':
                 // Check if action by Student
                if ($this->actionByStudent($lastEvent, $agreement->student)) {
                    $props['status'] = 'completed';
                    $props['signed_at'] = Carbon::now()->format('Y-m-d h:i:s');
                    $props['name'] = $agreement->student->name;
                } else {
                    return [];
                }
                break;
            default:
                $props['status'] =  strtolower(str_replace("_" , " ", $lastEvent['type']) );
                if ($this->actionByStudent($lastEvent, $agreement->student)) {
                    $props['signed_at'] = Carbon::now()->format('Y-m-d h:i:s');
                } else {
                    $props['signed_at'] = Carbon::now()->format('Y-m-d h:i:s');
                    $props['name'] = $user->name;
                }

            break;
        }
        $data = [
            'status'        => $props['status'],
            'uid'           => $agreement->uid,
            'service'       => $this->name,
            'properties'    => $props,
        ];
        return $data;
    }

    protected function actionByStudent($event, $student)
    {
        return $event['participantEmail'] == $student->email;
    }

    public function envelopeDocumentsList($agreement)
    {
        $properties = $this->plugin['properties'];

        $url =   $properties['api_access_point'] . env('ADOBESIGN_BASE_URL') . "/agreements/$agreement->uid/documents";

        $query = [];

        if ($response = $this->get($url, $query, false)) {
            $documents = json_decode($response, true);

            if (isset($documents['documents'])) {
                $documents['documents'][] = [
                    "id" => "00000",
                    "name" => __('Audit Trail'),
                    "mimeType" => "application/pdf",
                    "numPages" => 1,
                    "createdDate" => ""
                ];
                return $documents['documents'];
            }

            return [];
        }
    }

    public function getDownloadLink($payload)
    {
        $properties = $this->plugin['properties'];

        if ($payload['documentId'] == '00000') {
            $url =   $properties['api_access_point'] . env('ADOBESIGN_BASE_URL') . "/agreements/". $payload['uid'] ."/auditTrail";
        } else {
            $url =   $properties['api_access_point'] . env('ADOBESIGN_BASE_URL') . "/agreements/". $payload['uid'] ."/documents/" . $payload['documentId'];
        }
        $query = [];
        if ($response = $this->getFile($url, $query)) {
            return response()->json(
                [
                'status'        => 200,
                'response'      => 'success',
                'FileContent'   => base64_encode($response),
                'ContentType'   => 'application/pdf',
                'FileName'      => str_replace(" ", "_", $payload['documentName']).'.pdf'
            ]
            );
        }
    }


    protected function saveContract(Student $student, $response, Envelope $envelop, $submission = null)
    {
        $data = [
            'service'       => $this->name,
            'uid'           => $response['id'],
            'envelope_id'   => $envelop->id,
            'submission_id' => $submission,
            'title'         => $envelop->title,
            'status'        => $response['status'],
            'properties'    => $response,
            'student_id'    => $student->id,
            'user_id'       => Auth::guard('web')->user()->id,
        ];

        $contract = Contract::updateOrCreate(
            [
                'uid'           => $data['uid']
            ],
            $data
        );
        return $contract;
    }


    protected function getFileInfos($properties)
    {
        $fileInfos = [];

        foreach (array_filter($properties['templates']) as $template) {

            $fileInfos[]['libraryDocumentId'] = $template['id'];
        }

        return $fileInfos;
    }

    protected function getMergedData($data)
    {
        $mergedData = [];
        foreach ($data as $key=>$value) {
            $key = explode('|', $key);
            $mergedData[] = [
                "defaultValue" => $value,
                "fieldName" => $key[0]
            ];
        }
        return $mergedData;
    }

    /**
     * Generate Envelope with Multiple Documents Templates
     *
     * @param array $data
     * @param Student $student
     * @param Envelope $envelope
     * @return void
     */
    public function generateMultiTemplatesEnvelope($data, Student $student, Envelope $envelope, $submission = null, $signers = [])
    {
        $properties['templates'] = json_decode($envelope->properties['templates'], true);
        $plugin = $this->plugin['properties'];


        $signersDetails = $this->getSignersDetails($data, $student, $envelope->properties['signers'], $signers, $submission);
        try {
            $params = [
            "fileInfos" => $this->getFileInfos($properties),
            "name" => $envelope->title,
            "participantSetsInfo" => $signersDetails,
            "mergeFieldInfo" => $this->getMergedData($data),
            "signatureType" => "ESIGN",
            "externalId" => [
                "id"        => Str::random(8),
                "sender"    => Auth::user()->name
            ],
            "state" => "DRAFT"
        ];
            $url = $plugin['api_access_point'] . env('ADOBESIGN_BASE_URL') . "/agreements";
            $headers['Content-Type'] = 'application/json; charset=utf-8';

            $response = $this->post($url, $params, $headers, true);

            if ($response['id']) {
                $response['status'] = 'created';
                $response['statusDateTime'] = Carbon::now();

                $contract = $this->saveContract($student, $response, $envelope, $submission);

                // Generate the iframe
                $response = $this->generateEmbededSignature($response['id'], $plugin, $properties);

                return $response;
            }
        } catch (\Exception $e) {
            return $e->getMessage();
        }
    }



    public function reviewEnvelope($envelopeId, $studentId)
    {
        $plugin = $this->plugin['properties'];
        /* $data = [
            'envelopeId'    => $envelopeId,
            'service'       => $this->name,
        ]; */
        $properties['redirect_url']= url('/') . '/students/' . $studentId;
        return $this->generateEmbededSignature($envelopeId, $plugin, $properties);
    }


    protected function generateEmbededSignature($draftId, $plugin, $properties)
    {
        $url = $plugin['api_access_point'] . env('ADOBESIGN_BASE_URL') . "/agreements/$draftId/views";
        $headers['Content-Type'] = 'application/json; charset=utf-8';
        $params = [
                "name" => "ALL",
                "commonViewConfiguration" => [
                    "autoLoginUser" => true,
                    "locale" => "",
                    "noChrome" => false
                ]
            ];
        $response =  $this->post($url, $params, $headers, true);

        if (isset(reset($response['agreementViewList'])['url'])) {
            return reset($response['agreementViewList'])['url'];
        } else {
            return null;
        }
    }

    protected function getDocumentList($documents = null)
    {
        if (!$documents) {
            return null;
        }
        $list = [];
        foreach ($documents['documents'] as $document) {
            $list[$document['id']] = [
                'id'                => $document['id'],
                'documentIdGuid'    => $document['id'],
                'name'              => $document['name'],
                'type'              => $document['mimeType'],
            ];
        }
        return $list;
    }

    protected function getRecipients($envelopeId, $properties)
    {
        $url = env('ADOBESIGN_BASE_URL') ."/accounts/".$properties['account_id']."/envelopes/$envelopeId/recipients";
        $response = $this->get($url);
        return $response;
    }

    protected function getSchoolRoleDetails($schoolSigner = null)
    {
        if ($schoolSigner) {
            return  [
            "memberInfos" => [
                    [
                        "email" => $schoolSigner['email'],
                        "securityOption" => [
                        "authenticationMethod" => "Email",
                        "nameInfo" => [
                            "firstName" => $schoolSigner['first_name'],
                            "lastName" => $schoolSigner['last_name']
                            ]
                        ]
                    ]
                ],
                "order" => (int) $schoolSigner['order'],
                "role" => "SIGNER"
            ];
        }

        return null;
    }

    protected function getSignersDetails($data, $student, $signersRole, $signers, $submissionId = null)
    {
        if ($submissionId) {
            $submission = Submission::find($submissionId)->toArray()['data'];
        } else {
            $submission = $student->submissions->pluck('data')->toArray();
        }

        $signersList = [];

        foreach (json_decode($signersRole, true) as $signer) {
            switch ($signer['role']) {
                case 'Student':
                    if (isset($signers['signer_'. $signer['order']]['email'])) {
                        $signersList[] = [
                        "memberInfos" => [
                            [
                                "email" => $signers['signer_'. $signer['order']]['email'],
                                "securityOption" => [
                                "authenticationMethod" => "Email",
                                "nameInfo" => [
                                    "firstName" => $signers['signer_'. $signer['order']]['first_name'],
                                    "lastName" => $signers['signer_'. $signer['order']]['last_name']
                                    ]
                                ]
                            ]
                        ],
                        "order" => $signer['order'],
                        "role" => "SIGNER"
                    ];
                    } else {
                        $signersList[] = $this->getRoleDetails($data, $student, $signer['order']);
                    }
                    break;

                    case 'Parent':
                    // If the Student has a Parent Model
                    if (isset($signers['signer_'. $signer['order']]['email'])) {
                        $signersList[] = $this->extractRoleDetailsFromSubmission($signers['signer_'. $signer['order']], $submission, $signer['order']);
                    } else {
                        if ($parent = $student->parent) {
                            $signersList[] = $this->getRoleDetails($data, $parent, $signer['order']);
                        }
                    }
                    break;

                case 'Agent':
                    if (isset($signers['signer_'. $signer['order']]['email'])) {
                    } else {
                        if ($agent = $student->agent) {
                            $signersList[] = $this->getRoleDetails($data, $agent, $signer['order']);
                            break;
                        }
                    }

                    // no break
                default:
                    $signersList[] = [
                        "memberInfos" => [
                            [
                                "email" => $signer['email'],
                                "securityOption" => [
                                "authenticationMethod" => "Email",
                                "nameInfo" => [
                                    "firstName" => $signer['first_name'],
                                    "lastName" => $signer['last_name']
                                    ]
                                ]
                            ]
                        ],
                        "order" => $signer['order'],
                        "role" => "SIGNER"
                    ];
                    break;
            }
        }
        return $signersList;
    }

    protected function extractRoleDetailsFromSubmission($map, $submission, $order)
    {
        $email = isset($submission[$map['email']]) ? $submission[$map['email']] : '';
        $firstName = isset($submission[$map['first_name']]) ? $submission[$map['first_name']] : '';

        $lastName = isset($submission[$map['last_name']]) ? $submission[$map['last_name']] : '';

        return [
            "memberInfos" => [
                [
                    "email" => $email,
                    "securityOption" => [
                    "authenticationMethod" => "Email",
                    "nameInfo" => [
                        "firstName" => $firstName,
                        "lastName" => $lastName
                        ]
                    ]
                ]
            ],
            "order" => $order,
            "role" => "SIGNER"
        ];
    }

    protected function getRoleDetails($data, $item, $order = 2)
    {
        return  [
            "memberInfos" => [
                [
                    "email" => $item->email,
                    "securityOption" => [
                    "authenticationMethod" => "Email",
                    "nameInfo" => [
                        "firstName" => $item->first_name,
                        "lastName" => $item->last_name
                        ]
                    ]
                ]
            ],
            "order" => $order,
            "role" => "SIGNER"
        ];
    }



    protected function post($url, $formParams = null, $extraHeaders = null, $json = false)
    {
        $headers = [
            'Accept'        => 'application/json',
            'Authorization' => "Bearer " . $this->getToken(),
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
                'Authorization' => "Bearer " . $this->getToken()
            ],
        ];



        if ($query) {
            $params['query'] = $query;
        }



        $response = $this->client->request('GET', $url, $params);


        $response = $response->getBody()->getContents();


        if (!$encoded) {
            return $response;
        }

        return json_decode($response, true);
    }


    protected function getFile($url, $query = null)
    {
        $params = [
            'headers' => [
                'Accept'        => 'application/pdf',
                'Authorization' => "Bearer " . $this->getToken()
            ],
        ];

        if ($query) {
            $params['query'] = $query;
        }
        $response = $this->client->request('GET', $url, $params);
        $response = $response->getBody()->getContents();
        return $response;
    }

    protected function put($url, $body = null, $extraHeaders = null)
    {
        $headers = [
            'Accept'        => 'application/json',
            'Authorization' => "Bearer " . $this->getToken(),
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
