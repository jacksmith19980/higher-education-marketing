<?php
namespace App\Helpers\Plugins;

use Arr;
use Str;
use Crypt;
use Storage;
use App\User;
use Response;
use Carbon\Carbon;
use GuzzleHttp\Client;
use Webpatser\Uuid\Uuid;
use App\Tenant\Models\Plugin;
use App\Tenant\Models\Student;
use App\Tenant\Models\Contract;
use App\Tenant\Models\Envelope;
use App\Tenant\Models\Submission;
use Illuminate\Support\Facades\Auth;
use App\Helpers\Plugins\EsignatureAbstract;
use Stripe\Order;

class PandadocHelper extends EsignatureAbstract
{

    public $plugin;
    public $client;
    public $name = 'pandadoc';

    public $headers =  [
                'Accept'        => 'application/json',
    ];

    public function __construct()
    {
        $this->plugin = Plugin::where('name', $this->name)->first();
        $this->client =  new Client();

        $this->headers['Authorization'] = $this->getToken();
    }

    protected function getToken()
    {
        return 'API-Key ' . Crypt::decrypt($this->plugin['properties']['api_key']);
    }

    /**
     * Get a list of Templates
     *
     * @return array
     */
    public function getTemplates()
    {
        $query = [];
        try {
            $url =  env('PANDADOC_BASE_URL') . "/templates";
            return $this->get($url, $query);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getTemplateFields($templateId)
    {
        try {
            $url = env('PANDADOC_BASE_URL') . "/templates/$templateId/details";
            return $this->get($url);
        } catch (\Exception $e) {
            return null;
        }
    }

    public function getTemplateDocuments($templateId)
    {
        try {
            $properties = $this->plugin['properties'];
            $url = env('ADOBESIGN_BASE_URL') . "/accounts/".$properties['account_id']."/templates/$templateId/documents";
            return $this->get($url);
        }catch(\Exception $e) {
            return null;
        }
    }


    public function generateMultiTemplatesEnvelope($data, Student $student, Envelope $envelope, $submission = null, $signers = [])
    {
        $properties['templates'] = json_decode($envelope->properties['templates'], true);


        $recipients = $this->getSignersDetails($data, $student, $envelope->properties['signers'], $signers, $submission);


        $documents = [];
        foreach ($properties['templates'] as $template) {

            $params = [
                "name"              => $template['name'] . "-" . $student->name,
                "template_uuid"     => $template['id'],
                "recipients"        => $recipients,
                "tokens"            => $this->extractTokensData($data),
                "fields"            => $this->extractFieldsData($data),
                "metadata"          => $this->extractMetaData($data),
                "tags"              => [],
                "images"            => [],
                "pricing_tables"    => []
            ];

            $url = env('PANDADOC_BASE_URL') . "/documents";
            $response = $this->post($url , array_filter($params) , null , true);

            if($response['id']){
                sleep(5);
                // Change Docuemnt Status to Sent
                $sentDocument = $this->sendDocument($response['id']);

                if(isset($sentDocument['id'])){
                    $shareableLink = $this->getShareableLink($sentDocument['id']);
                    $documents[$sentDocument['id']] = 'https://app.pandadoc.com/s/' . $shareableLink['id'];


                    if (isset($sentDocument['id'])) {
                        // Save Contract
                        $contract = $this->saveContract($submission->student, $response, $envelope, $submission);
                    }
                }


            }
        }
        return $documents;
    }

    public function sendDocument($documentID)
    {
        try {
            $url = env('PANDADOC_BASE_URL') . "/documents/$documentID/send";
            $response =  $this->post($url , [
                'message'   => "Hello! This document was sent to you.",
                "silent"    => false
            ] , null , true);
            return $response;

        }catch (\Exception $e) {
            return null;
        }
    }

    public function getShareableLink($documentID)
    {
        $user = Auth::guard('web')->user();
        try {
            $url = env('PANDADOC_BASE_URL') . "/documents/$documentID/session";
        return $this->post($url , [
            'recipient' => $user->email,
            'lifetime'  => 3600
        ] , null , true);

        }catch (\Exception $e) {

            dump($e->getMessage());
            return null;
        }

    }

    protected function extractMetaData($data=[])
    {
        $meta = [];
        return count($meta) ? $meta : null;
    }

    protected function extractFieldsData($data=[])
    {

        $fields = [];
        foreach($data as $key=>$value){
            $temp = explode("|" , $key);
            if($temp[1] != 'token'){
                $fields[$temp[0]] = [
                    "value"=> $value
                ];
            }
        }
        return count($fields) ? $fields : null;
    }

    protected function extractTokensData($data=[])
    {
        $tokens = [];
        foreach($data as $key=>$value){
            $temp = explode("|" , $key);
            if($temp[1] == 'token'){
                $tokens[] = [
                    "name"=> $temp[0],
                    "value"=> $value
                ];
            }
        }
        return count($tokens) ? $tokens : null;
    }

    protected function getSignersDetails($data, $student, $signersRole, $signers, $submission = null)
    {

        $user = Auth::guard('web')->user();

        // add the current user to the list so he can review the contract
        $signersList = [];

        foreach (json_decode($signersRole, true) as $signer) {
            switch ($signer['role']) {
                case 'Student':
                    $signersList[] = [
                        "email"=> $student->email,
                        "first_name"=> $student->first_name,
                        "last_name"=> $student->last_name,
                        "role"=> "Student",
                        "signing_order" => $signer['order'],
                    ];
                    break;
                case 'School':
                    $signersList[] = [
                        "email"=> $signer['email'],
                        "first_name"=> $signer['first_name'],
                        "last_name"=> $signer['last_name'],
                        "role"=> "School",
                        "signing_order" => $signer['order'],
                    ];
                    break;

                case 'Admission':
                    $signersList[] = [
                        "email"=> $user->email,
                        "first_name"=> $user->firstname,
                        "last_name"=> $user->lastname,
                        "role"=> "Admission",
                        "signing_order" => $signer['order'],
                    ];
                    break;
                default:
                    $signersList[] = [
                        "email"=> $signer['email'],
                        "first_name"=> $signer['first_name'],
                        "last_name"=> $signer['last_name'],
                        "role"=> "School",
                        "signing_order" => $signer['order'],
                    ];
                    break;
            }
        }
        return $signersList;
    }


    protected function saveContract(Student $student, $response, Envelope $envelop, $submission = null)
    {
        $data = [
            'service'       => $this->name,
            'uid'           => $response['id'],
            'envelope_id'   => $envelop->id,
            'submission_id' => $submission,
            'title'         => $envelop->title,
            'status'        => 'created',
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


    public function getEventDetails($request)
    {
        $payload = $request->{0}['data'];

        if ($payload['id']) {
            $agreement = Contract::where('uid', $payload['id'])->with('user', 'student')->first();
        }
        if(!$agreement)
        {
            return false;
        }
        // Get Events Trail
        $props = [
            'envelopeId'        => $agreement->uid,
            'uri'               => '',
            'statusDateTime'    => Carbon::now()->format('Y-m-d h:i:s'),
            'documents'         =>  [],
        ];

        $actionBy = isset($payload['action_by']) ? $payload['action_by'] : $payload['sent_by'];

        switch ($payload['status']) {

            case 'document.viewed':

                $props['status']     = 'delivered';
                $props['created_at'] = Carbon::now()->format('Y-m-d h:i:s');
                $props['name']       = $actionBy['first_name'] . ' ' . $actionBy['last_name'];
                break;

            case 'document.completed':
                 // Check if action by Student
                $props['status'] = 'completed';
                $props['signed_at'] = Carbon::now()->format('Y-m-d h:i:s');

                $props['name'] = $actionBy['first_name'] . ' ' . $actionBy['last_name'];
                break;

            default:
                $props['status'] =  ucwords(str_replace("." , " ", $payload['status']) );
                $props['signed_at'] = Carbon::now()->format('Y-m-d h:i:s');
                $props['name'] = $actionBy['first_name'] . ' ' . $actionBy['last_name'];
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


    public function envelopeDocumentsList($contract)
    {
        $url = env('PANDADOC_BASE_URL') ."/documents/$contract->uid/details";
        $query = [];
        $documents = [];
        if ($response = $this->get($url, $query, false)) {
            $response = json_decode($response, true);
            if ($response && count($response)) {
                $documents['documents'][$response['id']] = $response['name'];
                return $documents['documents'];
            }
            return [];
        }
    }

    public function getDownloadLink($payload)
    {
        $url = env('PANDADOC_BASE_URL') ."/documents/". $payload['documentId'] ."/download";

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
                $url = env('PANDADOC_BASE_URL') ."/documents/$contract->uid/status";
                $user = Auth::guard('web')->user();
                $params = [
                    "status"    => 11, // Pandadoc Documentation
                    "note"      =>  "The contract has been declined by " . $user->name . ' at(' . Carbon::now()->format('Y-m-d h:i:s') . ')' ,
                    "notify_recipients" => true

                ];
                $response = $this->patch($url, $params , null, false);

                return ['envelopeId' => $contract->id];
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

    public function reviewEnvelope($envelopeId, $studentId)
    {
        $shareableLink = $this->getShareableLink($envelopeId);

        if(isset($shareableLink['id'])){
            return 'https://app.pandadoc.com/s/' . $shareableLink['id'];
        }
        return null;
    }

    /**
     * Guzzle POST Request
     *
     * @param [type] $url
     * @param [type] $formParams
     * @param [type] $extraHeaders
     * @param boolean $json
     * @return void
     */
    protected function post($url, $formParams = null, $extraHeaders = null, $json = false)
    {
        $headers = $this->headers;
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
            'headers' => $this->headers,
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


    protected function patch($url, $formParams = null, $extraHeaders = null, $json = false)
    {
        $headers = $this->headers;
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
        $response = $this->client->request('PATCH', $url, $params);
        $response = $response->getBody()->getContents();
        return json_decode($response, true);
    }

    protected function getFile($url, $query = null)
    {
        $params = [
            'headers' => $this->headers
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
