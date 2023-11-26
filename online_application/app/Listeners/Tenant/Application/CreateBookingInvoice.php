<?php

namespace App\Listeners\Tenant\Application;

use Carbon\Carbon;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\InvoiceStatus;
use App\Helpers\Invoice\InvoiceHelpers;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Events\Tenant\Application\ApplicationSubmissionEvent;

class CreateBookingInvoice
{
    /**
     * Handle the event.
     *
     * @param  ApplicationSubmissionEvent  $event
     * @return void
     */
    public function handle(ApplicationSubmissionEvent $event)
    {
        $createInvoice = $this->createInvoice($event->application, $event->submission->status);

        if (!$createInvoice) {
            return true;
        }
        // if application dose Not have a payment gateway
        // don't create invoice
        if (! $paymentGateWay = $event->application->PaymentGateway()) {
            return false;
        }

        /* if (!$invoice = $event->student->invoices()->where('submission_id', $event->submission->id)->first()) {

            $invoice = $event->student->invoices()->where('application_id', $event->application->id)->first();

        } */

        $invoice = $event->student->invoices()->where('submission_id', $event->submission->id)->first();

        if ($invoice) {
            return true;
        }
        // Check if the Application has NO fees
        if (! isset($event->application->properties['application_fees'])) {
            $booking = Booking::find($event->submission->booking_id);
            $enableInvoice = isset($booking->quotation->application->properties['auto_generate_invoice']) ? (int) $booking->quotation->application->properties['auto_generate_invoice'] : false;

            $totalPrice = optional($booking->invoice)['totalPrice'];
        } else {
            // Check if the Application HAS fees
            $enableInvoice = $createInvoice;
            $totalPrice = $event->application->properties['application_fees'];
        }

        $invoice = InvoiceHelpers::addSubmissionInvoice($event->application, $event->submission, $event->student, $totalPrice, Carbon::now());

        $invoiceable_payload = [
            'uid' => rand(100000, 1000000),
            'student_id' => $event->student->id,
            'quantity' => 1,
            'amount' => $totalPrice,
        ];

        $invoiceable_payload['title'] = $event->application->title;
        $event->submission->invoiceable()->save($invoice, $invoiceable_payload);
    }

    protected function createInvoice($application, $status)
    {
        if (isset($application->properties['invoice'])) {
            if ($status == 'Updated') {
                return $application->properties['invoice'] == 'auto_generate_invoice' && isset($application->properties['application_fees']);
            }

            if ($status == 'Submitted') {
                return $application->properties['invoice'] == 'after_application_submitted' && isset($application->properties['application_fees']);
            }
        } elseif (isset($event->application->properties['auto_generate_invoice'])) {
            return true;
        }

        return false;
    }
}
