<?php

namespace App\Listeners\Tenant\Student;

use App\Events\Tenant\Student\ChildAccountCreated;
use App\Jobs\Tenant\Integrations\IntegrateMautic;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\InvoiceStatus;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateBookingInvoice
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
     * @param  ChildAccountCreated  $event
     * @return void
     */
    public function handle(ChildAccountCreated $event)
    {
        $booking = Booking::find($event->booking);

        //Payment Gateway
        if (! $paymentGateWay = $booking->quotation->application->PaymentGateway()) {
            return false;
        }

        $enableInvoice = isset($booking->quotation->properties['auto_generate_invoice']) ? (int) $booking->quotation->properties['auto_generate_invoice'] : false;
        $invoice = new Invoice;
        $invoice->uid = rand(100000, 1000000);
        $invoice->total = $booking->invoice['totalPrice'];
        //$invoice->payment_gateway = 'flywire';
        $invoice->payment_gateway = $paymentGateWay->slug;
        $invoice->booking_id = $booking->id;
        $invoice->application_id = $booking->quotation->application->id;
        $invoice->student_id = $event->child->id;
        $invoice->enabled = $enableInvoice;
        $invoice->save();

        $invoiceURL = route('invoice.pay', ['school' => $event->school, 'invoice' => $invoice]);

        //dispatch( new InegrateMautic( $booking->quotation->application , null , $event->student , null ,'addInvoice' , null ) );

        $status = new InvoiceStatus;
        $status->status = 'Invoice Created';
        $invoice->status()->save($status);
    }
}
