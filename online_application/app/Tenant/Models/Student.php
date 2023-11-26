<?php

namespace App\Tenant\Models;

use App\User;
use Carbon\Carbon;
use App\Tenant\Traits\ForTenants;
use App\Tenant\Traits\Rewardable;
use App\Tenant\Traits\HasCampuses;
use App\Tenant\Traits\HasMessages;
use Illuminate\Support\Facades\Storage;
use Illuminate\Notifications\Notifiable;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Student extends Authenticatable
{
    use HasFactory;
    use Notifiable;
    use ForTenants;
    use Rewardable;
    use HasCampuses;
    use HasMessages;

    protected $guarded = [];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected $casts = [
        'phone'         => 'array',
        'params'        => 'array',
        'properties'    => 'array',
    ];

    public function submissions()
    {
        return $this->hasMany(Submission::class);
    }

    public function files()
    {
        return $this->hasMany(File::class);
    }

    public function agent()
    {
        return $this->belongsTo(Agent::class);
    }

    public function parent()
    {
        return $this->belongsTo(self::class, 'parent_id');
    }

    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    public function noAgentBookings()
    {
        return $this->bookings()->where('object', '!=', 'agent');
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function payments()
    {
        return $this->hasMany(InvoicePayments::class);
    }

    public function contracts()
    {
        return $this->hasMany(Contract::class);
    }

    public function campuses()
    {
        return $this->belongsToMany(Campus::class, 'campus_student');
    }

    public function token()
    {
        return $this->hasMany(Token::class);
    }

    public function cart()
    {
        return $this->hasMany(Cart::class);
    }

    public function groups()
    {
        return $this->belongsToMany(Group::class);
    }

    public function attendances($lesson_id = false)
    {
        if ($lesson_id) {
            return $this->hasMany(Attendance::class)->where('lesson_id', '=', $lesson_id);
        }

        return $this->hasMany(Attendance::class);
    }

    public function shareables()
    {
        return $this->morphMany(Shareable::class, 'shareable');
    }

    public function getOwnerAttribute()
    {
        if ($this->owner_id) {
            return User::find($this->owner_id);
        }

        return null;
    }

    public function getCampusAttribute()
    {
        return $this->campuses()->first();
    }

    public function enabledInvoices()
    {
        return $this->invoices()->where('enabled', '=', 1);
    }

    public function scopeByEmail($builder, $email)
    {
        return $builder->where('email', $email);
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getFullAddressAttribute()
    {
        $address = $this->address.', '.$this->city.', '.$this->country.', '.$this->postal_code;

        return $address == ', , , ' ? '' : $address;
    }

    public function getReferenceAttribute()
    {
        return 'VAR-'.(date('Y') + 1).'-'.$this->id;
    }

    public function getProfileImageAttribute()
    {
        if ($this->avatar) {
            return Storage::disk('s3')->temporaryUrl($this->avatar  , Carbon::now()->addMinutes(5));
        }

        return 'https://www.gravatar.com/avatar/' . md5($this->email) . '?s=200&r=pg&d=mp';
    }

    public function scopeOrderLastName($query)
    {
        return $query->orderBy('last_name');
    }

    public function scopeNotInGroup($query)
    {
        return $query->doesntHave('groups');
    }

    public function scopeStageStudents($query)
    {
        return $query->where('stage', 'student');
    }

    public function getTransactions()
    {
        $payments = $this->payments()->get();
        $invoices = $this->invoices()->get();

        return $payments->merge($invoices)->map(function ($transaction) {
            return (object) [
                'type' => $transaction instanceof Invoice ? 'Invoice' : 'Payment',
                'transaction' => $transaction,
                'created_at' => $transaction->created_at,
            ];
        });
    }

    public function getRecentTransactionDescriptionAttribute()
    {
        $recentTransaction = $this->getTransactions()->sortByDesc('created_at')->first();

        if (! $recentTransaction) {
            return 'N/A';
        }

        $transaction = $recentTransaction->transaction;
        $amount = $transaction instanceof Invoice ? $transaction->total : $transaction->amount_paid;
        $paymentMethod = $transaction->payment_method ? ' - ' . $transaction->payment_method : '';

        return $recentTransaction->type . $paymentMethod . ' - ' . money($amount);
    }
}
