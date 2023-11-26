<?php

namespace App\Events\Tenant\Quotation;

use App\Tenant\Models\Booking;
use App\Tenant\Models\Quotation;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class QuotationEmailRequested
{
    use Dispatchable,  SerializesModels;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public $data;
    public $quotation;
    public $booking;

    public function __construct(array $data, Quotation $quotation, Booking $booking)
    {
        $this->data = $data;
        $this->quotation = $quotation;
        $this->booking = $booking;
    }
}
