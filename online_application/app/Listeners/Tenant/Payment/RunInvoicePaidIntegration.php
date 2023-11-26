<?php

namespace App\Listeners\Tenant\Payment;

use App\Events\Tenant\Payment\InvoicePaid;
use App\Tenant\Models\InvoiceStatus;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;

class RunInvoicePaidIntegration
{
    use Integratable;

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
        $settings = Setting::byGroup();
        $invoice = $event->invoice;
        $student = $invoice->student;

        if (isset($settings['stages']['payment_completed'])) {
            $integration = $this->inetgration();

            $contact = $integration->getContact($student->email);
            if ($contact['total']) {
                $contactId = reset($contact['contacts'])['id'];

                // Edit Contact Paid Status
                $data= reset($contact['contacts'])['fields']['all'];
                $data['application_paid'] = 'Paid';
                $contact = $integration->editContact( array_filter($data) , $contactId);


                $integration->addToStage($contactId, $settings['stages']['payment_completed']);
            }
        }
    }
}
