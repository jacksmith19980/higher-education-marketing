<?php

namespace App\Listeners\Tenant\Application;

use App\Events\Tenant\Application\ApplicationSubmitted;
use App\Helpers\Invoice\InvoiceHelpers;

class AddInvoicesAfterSubmit
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
     * @param ApplicationSubmitted $event
     * @return void
     */
    public function handle(ApplicationSubmitted $event)
    {
        // return if the student had an invoice for this application
        $invoice = $event->student->invoices()->where('application_id', $event->application->id)->orWhere('submission_id', $event->submission->id)->first();
        if ($invoice) {
            return true;
        }
        dd($invoice);

        switch ($event->application->properties['payment_type']) {
            case 'full-amount':
                InvoiceHelpers::createFullAmountInvoice($event->application, $event->student);
                break;
            case 'fixed-amount':
                InvoiceHelpers::createFixedAmountInvoices($event->application, $event->student);
                break;
            case 'variable-amount':
                InvoiceHelpers::createVariableAmountInvoices($event->application, $event->student);
                break;
        }
    }
}
