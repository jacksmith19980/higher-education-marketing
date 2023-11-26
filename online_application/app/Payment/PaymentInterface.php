<?php

namespace App\Payment;

interface PaymentInterface
{
    public function processPayment($request);

    public function updateInvoiceStatus($response);
}
