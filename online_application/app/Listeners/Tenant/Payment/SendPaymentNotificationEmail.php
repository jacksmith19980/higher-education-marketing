<?php

namespace App\Listeners\Tenant\Payment;


use Mail;
use Illuminate\Queue\InteractsWithQueue;
use App\Events\Tenant\Payment\InvoicePaid;
use Illuminate\Contracts\Queue\ShouldQueue;
use App\Mail\Tenant\PaymentNotificationEmail;



class SendPaymentNotificationEmail
{

    /**
     * Handle the event.
     *
     * @param  InvoicePaid  $event
     * @return void
     */
    public function handle(InvoicePaid $event)
    {
        if(!isset($event->paymentGateWay)){
            return;
        }

        $invoice = $event->invoice;
        $properties = $event->paymentGateWay->properties;

        if(isset($properties['notification_emails'])){
            Mail::to(explode("," , $properties['notification_emails']))
                ->send(new PaymentNotificationEmail($event->response , $event->student));
        }

    }
}
