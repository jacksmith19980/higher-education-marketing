<?php

namespace App\Payment\Helcim;

use Illuminate\Support\Facades\Http;


class Helcim
{

    protected $accountID;
    protected $apiKey;
    protected $isSandBox;
    protected $headers;

    public function __construct($accountID, $apiKey , $isSandBox)
    {
        $this->accountID = $accountID;
        $this->apiKey = $apiKey;
        $this->isSandBox = $isSandBox;
        $this->headers = [
            'account-id' => $accountID,
            'api-token'  => $apiKey,
        ];

    }


    public function pay($payload)
    {

        $headers = $this->headers;
        $headers['accept'] = 'application/xml';
        $headers['content-type'] = 'application/x-www-form-urlencoded';

        $response = Http::withHeaders($headers)
        ->asForm()
        ->post('https://secure.myhelcim.com/api/card/purchase', $payload);

        $response = json_encode(simplexml_load_string($response->body()));
        $response = json_decode($response, true);
        return $response;

    }

}


?>
