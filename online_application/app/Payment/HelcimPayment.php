<?php

namespace App\Payment;

use App\Payment\Helcim\Helcim;
use App\Events\Tenant\Payment\InvoicePaid;

class HelcimPayment extends Payment implements PaymentInterface
{

    public function processPayment($request)
    {
        $props = $this->paymentGateway->properties;


        $helcim = new Helcim($props['account_id'] , $props['api_key'] , $props['is_sandbox_account']);

        $payload = [
            'cardToken'     => $request->cardToken,
            'cardF4L4Skip'  => true,
            'ecommerce'     => 1,
            'amount'        => $this->invoice->total,
            'ipAddress'     => $request->getClientIp(),
        ];
        $response = $helcim->pay($payload);

        if ($response['response']) {
            $message = 'Thank you for your payment';
        } else {
            $message = $response['responseMessage'];
        }

        return $this->processResponse((bool) $response['response'], false, $message, $response);

    }

    public function updateInvoiceStatus($response)
    {
        $data = [
            "transactionId",
            "type",
            "date",
            "time",
            "cardHolderName",
            "amount",
            "currency",
            "cardType",
            "customerCode",
        ];
        $res = [];
        foreach ($data as $element) {
            if(isset($response['transaction'][$element])){
                $res[$element] = $response['transaction'][$element];
            }
        }
        // update invoice status
        event(new InvoicePaid($this->invoice, $res , $this->paymentGateway));
    }

}
?>
