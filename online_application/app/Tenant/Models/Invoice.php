<?php

namespace App\Tenant\Models;

use Auth;
use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Invoice extends Model
{
    use HasFactory;
    use ForTenants;

    protected $fillable = [
        'uid',
        'total',
        'payment_gateway',
        'properties',
        'due_date',
        'submission_id',
    ];

    protected $casts = [
        'properties' => 'array',
    ];

    public function getRouteKeyName()
    {
        return 'uid';
    }

    public function application()
    {
        return $this->belongsTo(Application::class);
    }

    public function submission()
    {
        return $this->belongsTo(Submission::class);
    }

    public function applications()
    {
        return $this->morphedByMany(Application::class, 'invoiceable');
    }

    public function submissions()
    {
        return $this->morphedByMany(Submission::class, 'invoiceable');
    }

    public function courses()
    {
        return $this->morphedByMany(Course::class, 'invoiceable');
    }

    public function programs()
    {
        return $this->morphedByMany(Program::class, 'invoiceable');
    }

    public function addons()
    {
        return $this->morphedByMany(Addon::class, 'invoiceable');
    }

    public function invoiceables()
    {
        return Invoiceable::where('invoice_id', $this->id);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayments::class);
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function student()
    {
        return $this->belongsTo(Student::class);
    }

    public function status()
    {
        return $this->hasMany(InvoiceStatus::class);
    }

    public function lastStatus()
    {
        return $this->hasMany(InvoiceStatus::class)->orderBy('id', 'DESC')->first();
    }

    public function getIsPaidAttribute()
    {
        return $this->status()->where('status', 'Paid')->exists();
    }

    public function getIsNotPaidAttribute()
    {
        return ! $this->isPaid;
    }

    public function getPaymentMethodAttribute()
    {
        $paymentMethod = [];

        foreach ($this->status as $paymentStatus) {
            // Stripe
            if (isset($paymentStatus->properties['Card Brand'])) {
                $paymentMethod[] = 'Stripe: ' . ucfirst($paymentStatus->properties['Card Brand']) . ' ****' . $paymentStatus->properties['Card Last Four'];
            }

            // Paypal
            if (isset($paymentStatus->properties['Invoice Number'])) {
                $paymentMethod[] = 'Paypal: ' .' Order ID = ' . $paymentStatus->properties['Order ID'];
            }

            $monirisCardTypes = [
                'V' => 'Visa',
                'M' => 'MasterCard'
            ];

            // Moneris
            if (isset($paymentStatus->properties['ReceiptId'])) {
                $paymentMethod[] = 'Moneris: ' .' ReferenceNum = ' . $paymentStatus->properties['ReferenceNum'] . '(' . (isset($monirisCardTypes[$paymentStatus->properties['CardType']]) ? $monirisCardTypes[$paymentStatus->properties['CardType']] : $paymentStatus->properties['CardType']) . ')';
            }

            // Creditagricole
            if (isset($paymentStatus->properties['Device Number'])) {
                $paymentMethod[] = 'Creditagricole: ' .' Transaction Number = ' . $paymentStatus->properties['Transaction Number'] . ', Date = ' . $paymentStatus->properties['Date of the transaction'] . ' ' . $paymentStatus->properties['Time of the transaction'];
            }

            // Bambora
            if (isset($paymentStatus->properties['order_number'])) {
                $paymentMethod[] = 'Bambora: ' .' Payment Method = ' . $paymentStatus->properties['payment_method'];
            }

            // Flywire
            if (isset($paymentStatus->properties['Transaction Id']) and isset($paymentStatus->properties['At'])) {
                $paymentMethod[] = 'Flywire: ' .' Transaction Id = ' . $paymentStatus->properties['Transaction Id'];
            }

            // Datatrans
            if (isset($paymentStatus->properties['ACQ Authorization Code'])) {
                $paymentMethod[] = 'Datatrans: ' .' Transaction Id = ' . $paymentStatus->properties['Transaction Id'];
            }
        }

        return implode(' - ', $paymentMethod);
    }

    public function canBePaid(PaymentGateway $gateway)
    {
        return $this->status->last()->status == 'Paid' && $gateway->slug != 'creditagricole';
    }
}
