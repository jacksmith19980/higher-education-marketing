<?php

namespace App\Payment\moneris;

class riskTransaction{

    var $txn;
    var $attributeAccountInfo = null;
    var $sessionAccountInfo = null;

    function __construct($txn)
    {
        $this->txn=$txn;
    }

    function getTransaction()
    {
        return $this->txn;
    }

    function getAttributeAccountInfo()
    {
        return $this->attributeAccountInfo;
    }

    function setAttributeAccountInfo($attributeAccountInfo)
    {
        $this->attributeAccountInfo = $attributeAccountInfo;
    }

    function getSessionAccountInfo()
    {
        return $this->sessionAccountInfo;
    }

    function setSessionAccountInfo($sessionAccountInfo)
    {
        $this->sessionAccountInfo = $sessionAccountInfo;
    }
}//end class RiskTransaction
