<?php

namespace App\Listeners\Tenant\Payment;

use App\Events\Tenant\Payment\InvoicePaid;
use App\Helpers\Application\PaymentHelpers;
use App\Tenant\Models\InvoiceStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Queue\InteractsWithQueue;

class UpdateInvoiceStatus
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  InvoicePaid  $event
     * @return void
     */
    public function handle(InvoicePaid $event)
    {
        $invoice = $event->invoice;

        PaymentHelpers::addPayment($invoice, $invoice->student_id, $invoice->total);

        $invoiceStatus = new InvoiceStatus();
        $invoiceStatus->status = 'Paid';
        $invoiceStatus->properties = $event->response;

        $invoiceStatus->invoice_id = $invoice->id;
        if ($invoiceStatus->save()) {
            return true;
        }
    }
}
