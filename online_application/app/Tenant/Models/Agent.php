<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Foundation\Auth\User as Authenticatable;

class Agent extends Authenticatable
{
    use ForTenants;

    public static $modelName = 'agents';

    protected $fillable = [
        'activation_token',
        'first_name',
        'last_name',
        'email',
        'password',
        'phone',
        'active',
        'is_admin',
        'agency_id',
    ];

    protected $hidden = [
        'password', 'remember_token',
    ];

    public function students()
    {
        return $this->hasMany(Student::class);
    }

    public function agency()
    {
        return $this->belongsTo(Agency::class);
    }

    public function scopeEmailAndPassword(Builder $builder, $email, $password)
    {
        return $builder->where('email', $email)->where('password', $password);
    }

    public function scopeByActivationColumns(Builder $builder, $token, $email)
    {
        return $builder->where('activation_token', $token)->where('email', $email)->whereNotNull('activation_token');
    }

    public function getIsSuperAdminAttribute()
    {
        return $this->roles == 'Super Admin';
    }

    public function getIsAgentAdminAttribute()
    {
        return $this->roles == 'Super Admin' || $this->roles == 'Agency Admin';
    }

    /**
     * Get all bookings
     */
    public function bookings()
    {
        return $this->hasMany(Booking::class, 'user_id');
    }

    /**
     * Get agent bookings
     */
    public function agentBookings()
    {
        return $this->bookings()->where('object', '=', 'agent');
    }

    public function getNameAttribute()
    {
        return $this->first_name.' '.$this->last_name;
    }

    public function getAvatarAttribute()
    {
        return 'https://www.gravatar.com/avatar/'.md5($this->email).'?s=200&r=pg&d=mp';
    }

    /**
     * by email
     */
    public function scopeByEmail($builder, $email)
    {
        return $builder->where('email', $email);
    }

    /**
     * Get Password Reset token
     */
    public function token()
    {
        return $this->hasMany(AgentToken::class);
    }
}
