<?php

namespace App\Payment;

use App\Payment\MonerisPayment;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\PaymentGateway;
use Illuminate\Http\Request;

class Payment
{
    protected $request;

    protected $invoice;

    protected $paymentGateway;

    public function __construct(PaymentGateway $paymentGateway, Request $request, Invoice $invoice)
    {
        $this->request = $request;
        $this->invoice = $invoice;
        $this->paymentGateway = $paymentGateway;
    }

    public function pay()
    {
        if (! $this->request->payment) {
            return false;
        }
        $gateWayClass = 'App\\Payment\\'.ucwords($this->request->payment).'Payment';
        $gateWay = new $gateWayClass($this->paymentGateway, $this->request, $this->invoice);
        $response = $gateWay->processPayment($this->request);
        return $response;
    }

    /**
     * Process Payment Response
     *
     * @param [boolean] $isSuccessful
     * @param [boolean] $isAjax
     * @param string $message
     * @param [type] $response
     * @return void
     */
public function processResponse($isSuccessful, $isAjax, $message, $response)
    {

        if ($isSuccessful) {

            //Update invocie status
            $this->updateInvoiceStatus($response);


            // Process Ajax Request
            if ($isAjax) {
                return [
                    'status'    => 200,
                    'response'  => 'success',
                    'message'   => view('front.layouts.core.alert-success', compact('message'))->render(),
                ];
            }

            // Redirect After Payment
            if (isset($this->paymentGateway->properties['payment_thank_you'])) {
                return redirect($this->paymentGateway->properties['payment_thank_you']);
            } else {
                return redirect()->back()->withSuccess($message);
            }
        } else {

            // Process Ajax Request
            if ($isAjax) {
                return [
                    'status'    => 401,
                    'response'  => 'fail',
                    'message'   => view('front.layouts.core.alert-failer', compact('message'))->render(),
                ];
            }
            session()->put('errors', $message);
            return redirect()->back()->withErrors($message);
        }
    }
}
