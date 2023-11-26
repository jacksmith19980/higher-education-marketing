<?php

namespace App\Integrations\Webhook;

use App\Integrations\BasicIntegration;
use GuzzleHttp\Client;

class Webhook extends BasicIntegration
{
    protected $method;
    protected $url;
    protected $client;
    protected $name = 'Webhook';
    public function __construct($settings)
    {
        $this->method = isset($settings['webhook_method']) ? $settings['webhook_method'] : $settings['method'];
        $this->url = isset($settings['webhook_url']) ? $settings['webhook_url'] : $settings['url'];
        $this->client = new Client();
    }

    public function createNewContact($lead, $contactType, $stage = null, $invoiceURL = null)
    {
        if ($invoiceURL) {
            $lead['invoice_url'] = $invoiceURL;
        }

        return $this->createContact($lead, $contactType);
    }

    public function createContact($lead, $contactType, $action = 'create.lead')
    {
        $data['action'] = $action;
        $data['data'][$contactType] = $lead;

        return $this->request(null, null, $data, null);
    }

    public function request($method, $url, $params, $options)
    {
        if (! $method) {
            $method = $this->method;
        }
        if (! $url) {
            $url = $this->url;
        }
        return $this->{strtolower($method)}(
            $url,
            $params,
            isset($options['headers']) ? $options['headers'] : null,
            isset($options['json']) ?  $options['json'] : null,
            true
        );
    }

    protected function post($url, $formParams = null, $extraHeaders = null, $json = false, $encoded = true)
    {
        $headers = [
            'Accept'        => 'application/json',
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

        if (! $encoded) {
            return $response;
        }
        return json_decode($response, true);
    }

    /**
     * Guzzle Get Request
     *
     * @param [string] $url
     * @param [array] $query
     * @return array
     */
    protected function get($url, $query, $extraHeaders = null, $json = false, $encoded = true)
    {
        $headers = [
            'Accept'        => 'application/json',
        ];

        if ($extraHeaders) {
            $headers = $headers + $extraHeaders;
        }

        $params = [
            'headers' => $headers,
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

    public function getRegisterationDefaults($settings , $data)
    {
        return $data;
    }
}
