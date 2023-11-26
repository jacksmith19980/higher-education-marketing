<?php

namespace App\Payment\moneris;

class MpiTransaction
{
    var $txn;

    function __construct($txn)
    {
        $this->txn=$txn;
    }

    function getTransaction()
    {
        return $this->txn;
    }
}//end class MpiTransaction
