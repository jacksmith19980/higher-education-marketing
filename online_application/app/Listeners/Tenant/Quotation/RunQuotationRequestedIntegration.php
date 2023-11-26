<?php

namespace App\Listeners\Tenant\Quotation;

use App\Events\Tenant\Quotation\QuotationEmailRequested;
use App\Helpers\Quotation\QuotationHelpers;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Setting;
use App\Tenant\Traits\Integratable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class RunQuotationRequestedIntegration
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
        $contact = $event->data;
        $setting = Setting::byGroup('stages');
        $booking = $event->booking;
        $quotation = $event->quotation;

        $programs = $this->extractPrograms($booking);
        $campus = $this->extractCampus($booking);

        // Push Agent To integrations
        if ($integration = $this->inetgration()) {
            $request_type = isset($quotation['properties']['integrations']['mautic']['request_type']) ?
            $quotation['properties']['integrations']['mautic']['request_type'] : 'Quote';

            /*$quoteDetails = urlencode(QuotationHelpers::getBookingDetails($booking->invoice['details']));*/
            $quoteDetails = QuotationHelpers::getBookingDetails($booking->invoice['details']);

            $data = [

                'title'         => $contact['title'],
                'firstname'     => $contact['first_name'],
                'lastname'      => $contact['last_name'],
                'phone'         => $contact['phone'],
                'email'         => $contact['email'],
                'contact_type'  => 'Lead',
                'request_type'  => $request_type,

                'total_gross'   => $booking->invoice['totalPrice'],

                'programs'      => $programs,
                'campus'        => $campus,

                'booking_link'  => route('school.register', [
                            'school'    => request('school'),
                            'booking'   => $booking->id,
                            'user'      => $booking->user_id,
                ]),
                'quote_details' => $quoteDetails,
            ];

            // Create Agent
            $contact = $integration->createNewContact($data, 'Lead', $setting['stages']['booking_email_stage']);

            if (isset($contact['contact']['id'])) {
                $note = [
                    'lead'  => $contact['contact']['id'],
                    'type'  => 'general',
                    'title' => 'Request Quotation - '.date('l j F Y'),
                    'text'  =>  $quoteDetails,
                ];
                $integration->addNote($note);
            }
        }
    }

    protected function extractPrograms($booking)
    {
        $programs = [];
        if (isset($booking->invoice['details']['courses'])) {
            foreach ($booking->invoice['details']['courses'] as $course) {
                $programs[] = $course['title'];
            }
        }

        return $programs;
    }

    protected function extractCampus($booking)
    {
        $campus = '';
        if (isset($booking->invoice['details']['courses'])) {
            foreach ($booking->invoice['details']['courses'] as $course) {
                $campus = $course['campus_title'];
            }
        }

        return $campus;
    }
}
