<?php

namespace App\Payment;

use App\Events\Tenant\Payment\InvoicePaid;
use enumit\Bambora\Gateway;

class BamboraPayment extends Payment implements PaymentInterface
{
    public function processPayment($request)
    {
        $merchant_id = $this->paymentGateway->properties['merchant_id'];
        $passcode = $this->paymentGateway->properties['passcode'];
        $total = $this->invoice->total;
        $order_number = $this->invoice->uuid;
        $expiry = explode('/', $this->request->cc_expiry);
        $isAjax = ($this->request->has('ajax')) ? true : false;

        $payment = Gateway::getPaymentRequest($merchant_id, $passcode, 'v1');

        $response = $payment->makePayment(
            [
                'order_number'   => $order_number,
                'amount'         => $total.'.00',
                'payment_method' => 'card',
                'card'           => [
                    'number'       => $this->request->cc_number,
                    'name'         => 'Card Holder',
                    'expiry_month' => $expiry[0],
                    'expiry_year'  => $expiry[1],
                    'cvd'          => $this->request->cc_cvs,
                ],
            ]
        );

        $isSuccessfull = (isset($response['approved']) && $response['approved'] == '1') ? true : false;
        if ($isSuccessfull) {
            $message = 'Thank you for your payment';
        } else {
            $message = $response['message'];
        }

        return $this->processResponse($isSuccessfull, $isAjax, $message, $response);
    }

    public function updateInvoiceStatus($response)
    {
        $data = ['id', 'authorizing_merchant_id', 'approved', 'message_id', 'message', 'auth_code', 'created',
                  'order_number', 'type', 'payment_method', 'amount', ];
        $res = [];
        foreach ($data as $element) {
            $res[$element] = $response[$element];
        }
        // update invoice status
        event(new InvoicePaid($this->invoice, $res));
    }
}
