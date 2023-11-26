<?php

namespace App\Events\Tenant\Payment;

use App\Tenant\Models\Invoice;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;
use App\Tenant\Models\PaymentGateway;
use Auth;

class InvoicePaid
{
    use Dispatchable, SerializesModels;

    public $student;
    public $invoice;
    public $response;
    public $paymentGateWay;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct(Invoice $invoice, $response ,PaymentGateway $paymentGateWay = null)
    {
        $this->student = Auth::guard('student')->user();
        $this->invoice = $invoice;
        $this->response = $response;
        $this->paymentGateWay = $paymentGateWay;
    }
}
