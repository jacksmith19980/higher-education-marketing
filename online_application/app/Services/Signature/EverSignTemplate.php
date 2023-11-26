<?php

namespace App\Services\Signature;

use App\School;
use App\Tenant\Models\Plugin;
use Auth;
use Eversign\ApiRequest;
use Eversign\Client;
use Eversign\DocumentTemplate;
use Eversign\Field;
use Eversign\Signer;
use GuzzleHttp\Client as Http;
use GuzzleHttp\Exception\GuzzleException;
use Illuminate\Http\Request;

class EverSignTemplate extends DocumentTemplate
{
    private $language;

    public function getLanguage()
    {
        return $this->language;
    }

    public function setLanguage($language)
    {
        $this->language = $language;
    }
}
