<?php

namespace App\Payment;

use App\Events\Tenant\Payment\InvoicePaid;
use App\Payment\Payment;
use App\Payment\PaymentInterface;
use App\Tenant\Models\Invoice;
use Illuminate\Http\Request;

class CreditagricolePayment extends Payment implements PaymentInterface
{
    public function processPayment($request)
    {
    }

    public function response(Request $request)
    {
        $isSuccessful = ($request->reponse == '00000' && isset($request->transaction_number)) ? true : false;

        return $this->processResponse($isSuccessful, true, 'Paid Successfully', $request->all());
    }

    public function updateInvoiceStatus($response)
    {
        mail('mattalah@higher-education-marketing.com', 'Debug subject', json_encode($response));

        $invoice = Invoice::where('uid', $response['ref'])->first();
        $details = [
            'Amount'                    => $response['amount'],
            'Authorization Number'      => $response['auto'],
            'Device Number'             => $response['appel'],
            'Subscription Number'       => $response['abo'],
            'Transaction Number'        => $response['transaction_number'],
            'Country'                   => $response['pays'],
            'IP Country'                => $response['ip'],
            'Time of the transaction'   => $response['created_at_time'],
            'Date of the transaction'   => $response['created_at_date'],
        ];

        // update invoice status
        event(new InvoicePaid($invoice, $details));
    }
}
