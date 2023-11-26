<?php

namespace App\Listeners\Tenant\Assistant;

use App\Events\Tenant\Assistant\AssistantEmailRequested;
use App\Mail\Tenant\AssistantEmail;
use App\Tenant\Traits\Integratable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Mail;

class SendAssistantEmail
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
    public function handle(AssistantEmailRequested $event)
    {
        $props = $event->assistantBuilder->properties;
        if (isset($props['thank_you_email_from_mautic']) && isset($props['mautic_email'])) {
            $integration = $this->inetgration();
            $integration->sendEmail((int) $props['mautic_email'], [$event->data['email']]);
        } else {
            Mail::to($event->data['email'])->send(new AssistantEmail($event->data, $event->assistantBuilder, $event->assistant));
        }
    }
}
