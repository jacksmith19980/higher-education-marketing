<?php

namespace App\Mail\Tenant;

use App\Tenant\Models\Invoice;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;

class SendInvoiceReminderEmail extends Mailable
{
    use  SerializesModels;

    public $data;
    public $student;
    public $invoice;
    public $settings;

    /**
     * Create a new message instance.
     *
     * @return void
     */
    public function __construct(Student $student, Invoice $invoice, array $data)
    {
        $this->data = $data;
        $this->student = $student;
        $this->invoice = $invoice;
        $this->settings = Setting::byGroup();
    }

    /**
     * Build the message.
     *
     * @return $this
     */
    public function build()
    {
        return $this
            ->from($this->settings['school']['from_email'], $this->settings['school']['from_name'])
            ->markdown('back.emails.school.invoice-reminder-email')->subject($this->data['subject']);
    }
}
