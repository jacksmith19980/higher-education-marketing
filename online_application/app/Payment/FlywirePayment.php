<?php

namespace App\Payment;

use App\Events\Tenant\Payment\InvoicePaid;
use App\Payment\Payment;
use App\Payment\PaymentInterface;
use App\Tenant\Models\Invoice;
use Illuminate\Http\Request;

class FlywirePayment extends Payment implements PaymentInterface
{
    public function processPayment($request)
    {
    }

    public function response(Request $request)
    {
        $isSuccessful = ($request->status == 'guaranteed') ? true : false;

        return $this->processResponse($isSuccessful, true, 'Paid Successfully', $request->toArray());
    }

    public function updateInvoiceStatus($response)
    {
        $invoice = Invoice::where('uid', $response['callback_id'])->first();
        $details = [
            'Transaction Id'           => $response['id'],
            'Amount'                   => $response['amount'],
            'Status'                   => $response['status'],
            'At'                       => $response['at'],
        ];
        // update invoice status
        event(new InvoicePaid($invoice, $details));
    }
}
