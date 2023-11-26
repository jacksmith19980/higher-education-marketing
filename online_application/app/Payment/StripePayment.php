<?php

namespace App\Payment;

use App\Events\Tenant\Payment\InvoicePaid;
use App\Payment\Payment;
use App\Payment\PaymentInterface;
use App\Tenant\Models\Invoice;
use Illuminate\Http\Request;
use Response;
use Stripe\Stripe;

class StripePayment extends Payment implements PaymentInterface
{
    public function processPayment($request)
    {
        \Stripe\Stripe::setApiKey($this->paymentGateway->properties['secret_api_key']);
        $response = \Stripe\Charge::create([
        'amount'        => $this->request->amount,
        'currency'      => $this->request->currency,
        'description'   => $this->request->description,
        'source'        => $this->request->id,
        'metadata'      => [
            'id'            => $this->invoice->id,
            'uid'           => $this->invoice->uid,
            'student'       => $this->invoice->student_id,
            'booking'       => $this->invoice->booking_id,
            'application'   => $this->invoice->application_id,
        ],
        ]);

        return [
            'status'    => 200,
            'response'  => $response,
            'message'   => 'Paid Successfully',
        ];
    }

    public function response(Request $request)
    {
        $isSuccessful = $request->response['paid'];
        $response = $this->processResponse($isSuccessful, true, 'Thank you for your payment', $request->toArray());

        return Response::json($response);
    }

    public function updateInvoiceStatus($response)
    {
        $invoice = Invoice::where('uid', $response['response']['metadata']['uid'])->firstOrFail();
        $details = [
            'Id'                => $response['response']['id'],
            'Created'           => $response['response']['created'],
            'Amount'            => $response['response']['amount'],
            'Payment Method'    => $response['response']['payment_method'],
            'receipt_url'       => $response['response']['receipt_url'],
            'Card Brand'        => $response['response']['payment_method_details']['card']['brand'],
            'Card Last Four'    => $response['response']['payment_method_details']['card']['last4'],
        ];
        // update invoice status
        event(new InvoicePaid($invoice, $details));
    }
}
