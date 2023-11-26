<?php

namespace App\Payment;

use App\Events\Tenant\Payment\InvoicePaid;
use App\Payment\Payment;
use App\Payment\PaymentInterface;
use App\Tenant\Models\Invoice;
use Illuminate\Http\Request;

class PaypalPayment extends Payment implements PaymentInterface
{
    public function processPayment($request)
    {
    }

    public function response(Request $request)
    {
        $isSuccessful = ($request->details['status'] == 'COMPLETED') ? true : false;

        return $this->processResponse($isSuccessful, true, __('Thank you for your payment!'), $request->all());
    }

    public function updateInvoiceStatus($response)
    {
        $invoice = Invoice::where('uid', $response['invoice_number'])->first();
        $details = [
            'Order ID'                  => $response['orderID'],
            'Amount'                    => $response['details']['purchase_units'][0]['amount']['value'],
            'Status'                    => $response['details']['status'],
            'At'                        => $response['details']['create_time'],
            'Invoice Number'            => $response['invoice_number'],
        ];
        // update invoice status
        event(new InvoicePaid($invoice, $details));
    }
}
