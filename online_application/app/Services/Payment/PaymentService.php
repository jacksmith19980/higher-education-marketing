<?php

namespace App\Services\Payment;

interface PaymentService
{
    public function paymentProcess($payment_details);
}
