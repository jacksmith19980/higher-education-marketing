<?php

namespace App\Listeners\Tenant\Quotation;

use App\Events\Tenant\Quotation\QuotationEmailRequested;
use App\Mail\Tenant\QuotationEmail;
use App\Tenant\Traits\Integratable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendQuotationEmail
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
     * @param  QuotationEmailRequested  $event
     * @return void
     */
    public function handle(QuotationEmailRequested $event)
    {
        $props = $event->quotation->properties;
        if (isset($props['thank_you_email_from_mautic']) && isset($props['mautic_email'])) {
            $integration = $this->inetgration();
            $integration->sendEmail((int) $props['mautic_email'], [$event->data['email']]);
        } else {
            Mail::to($event->data['email'])->send(new QuotationEmail($event->data, $event->quotation, $event->booking));
        }
    }
}
