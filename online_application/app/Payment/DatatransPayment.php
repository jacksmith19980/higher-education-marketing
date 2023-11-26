<?php

namespace App\Payment;

use App\Events\Tenant\Payment\InvoicePaid;
use App\Payment\Payment;
use App\Payment\PaymentInterface;
use App\Tenant\Models\Invoice;
use Illuminate\Http\Request;

class DatatransPayment extends Payment implements PaymentInterface
{
    public function processPayment($request)
    {
    }

    public function response(Request $request)
    {
        $isSuccessful = ($request->status == 'success') ? true : false;

        return $this->processResponse($isSuccessful, true, 'Paid Successfully', $request->toArray());
    }

    public function updateInvoiceStatus($response)
    {
        $invoice = Invoice::where('uid', $response['refno'])->first();
        $details = [
            'Transaction Id'           => $response['uppTransactionId'],
            'Authorization Code'       => $response['authorizationCode'],
            'Expiry Year'              => $response['expy'],
            'Expiry Month'             => $response['expm'],
            'ACQ Authorization Code'   => $response['acqAuthorizationCode'],
            'Merchant Id'              => $response['merchantId'],
            'Customer Country'         => $response['returnCustomerCountry'],
            'Currency'                 => $response['currency'],
            'Ref No'                   => $response['refno'],
            'Amount'                   => $response['amount'],
            'Payment Method'           => $response['pmethod'],
            'Response Message'         => $response['responseMessage'],
        ];
        // update invoice status
        event(new InvoicePaid($invoice, $details));
    }
}
