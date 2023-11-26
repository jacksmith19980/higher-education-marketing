<?php

namespace App\Mail\Tenant;

use App\Helpers\Quotation\QuotationHelpers;
use App\Tenant\Models\Setting;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use Token;

class QuotationEmail extends Mailable
{
    use Queueable, SerializesModels;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public $quotation;
    public $data;
    public $booking;
    public $settings;
    public $subject;
    public $content = null;

    public function __construct($data, $quotation, $booking)
    {
        $this->settings = Setting::byGroup();
        $this->quotation = $quotation;
        $this->data = $data;
        $this->booking = $booking;

        $this->subject = isset($quotation->properties['thank_you_subject']) ? $quotation->properties['thank_you_subject'] : $this->quotation->title;

        $map = [
            'TITLE'             => $data['title'],
            'FIRST_NAME'        => $data['first_name'],
            'LAST_NAME'         => $data['last_name'],
            'EMAIL'             => $data['email'],
            'BOOKING_DETAILS'   => QuotationHelpers::getBookingDetails($booking->invoice['details']),
            'BOOKING_BUTTON'    => $this->getBookingButton($booking),
            'PRICE'             => $this->settings['school']['default_currency'].number_format($booking->invoice['totalPrice']),
        ];

        if (isset($quotation->properties['thank_you_email'])) {
            $this->content = Token::replace($map, $quotation->properties['thank_you_email']);
        }
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        $props = $this->quotation->properties;

        $sender_name = isset($props['thank_you_sender_name']) ? $props['thank_you_sender_name'] : $this->settings['school']['from_name'];

        $sender_email = isset($props['thank_you_sender_email']) ? $props['thank_you_sender_email'] : $this->settings['school']['from_email'];

        return $this
            ->from($sender_email, $sender_name)
            ->subject($this->subject)
            ->markdown('front.quotations.mail.quotation-email', ['content' => $props]);
    }

    /**
     * Construct Booking Button
     *
     * @param Booking $booking
     * @return string
     */
    protected function getBookingButton($booking)
    {
        return '<a style="font-family:Avenir,Helvetica,sans-serif;box-sizing:border-box;border-radius:3px;color:#fff;display:inline-block;text-decoration:none;background-color:#3097d1;border-top:10px solid #3097d1;border-right:18px solid #3097d1;border-bottom:10px solid #3097d1;border-left:18px solid #3097d1" href="'.route('school.register', [
            'school' => request('school'),
            'booking' => $booking->id,
            'user' => $booking->user_id,
        ]).'">Book Now</a>';
    }
}
