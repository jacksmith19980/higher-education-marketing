<?php
namespace enumit\Bambora;

use enumit\Bambora\requests\PaymentRequest;
use enumit\Bambora\requests\ProfilesRequest;
use enumit\Bambora\requests\ReportingRequest;
use enumit\Bambora\requests\TokenizationRequest;

class Gateway
{
    public static function getPaymentRequest($merchantId, $passcode, $version)
    {
        return new PaymentRequest($merchantId, $passcode, $version);
    }

    public static function getReportingRequest($merchantId, $passcode, $version)
    {
        return new ReportingRequest($merchantId, $passcode, $version);
    }

    public static function getProfilesRequest($merchantId, $passcode, $version)
    {
        return new ProfilesRequest($merchantId, $passcode, $version);
    }

    public static function getTokenizationRequest($merchantId, $passcode, $version)
    {
        return new TokenizationRequest($merchantId, $passcode, $version);
    }
}
