<?php

namespace App\Tenant\Models;

use App\Tenant\Traits\ForTenants;
use Carbon\Carbon;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promocode extends Model
{
    use HasFactory;
    use ForTenants;

    public static $modelName = 'promocodes';

    public $timestamps = false;

    protected $fillable = ['code', 'reward', 'is_disposable', 'commence_at', 'expires_at', 'quantity', 'type'];

    protected $casts = [
        'commence_at' => 'datetime',
        'expires_at' => 'datetime',
        'is_disposable' => 'boolean',
        'data' => 'array',
        'quantity' => 'integer',
    ];

    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = config('promocodes.table', 'promocodes');
    }

//    /**
//     * Get the users who is related promocode.
//     *
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
//     */
//    public function users()
//    {
//        return $this->belongsToMany(
//            config('promocodes.user_model'),
//            config('promocodes.relation_table'),
//            config('promocodes.foreign_pivot_key', 'user_id'),
//            config('promocodes.related_pivot_key', 'user_id')
//        )->withPivot('used_at');
//    }

    public function quotations()
    {
        return $this->morphedByMany(Quotation::class, 'promocodeable');
    }

    public function bookings()
    {
        return $this->morphedByMany(Booking::class, 'promocodeable');
    }

    public function users()
    {
        return $this->morphedByMany(config('promocodes.user_model'), 'promocodeusable');
    }

    /**
     * Query builder to find promocode using code.
     *
     * @param $query
     * @param $code
     *
     * @return mixed
     */
    public function scopeByCode($query, $code)
    {
        return $query->where('code', $code);
    }

    /**
     * Query builder to get disposable codes.
     *
     * @param $query
     * @return mixed
     */
    public function scopeIsDisposable($query)
    {
        return $query->where('is_disposable', true);
    }

    /**
     * Query builder to get non-disposable codes.
     *
     * @param $query
     * @return mixed
     */
    public function scopeIsNotDisposable($query)
    {
        return $query->where('is_disposable', false);
    }

    /**
     * Query builder to get expired promotion codes.
     *
     * @param $query
     * @return mixed
     */
    public function scopeExpired($query)
    {
        return $query->whereNotNull('expires_at')->whereDate('expires_at', '<=', Carbon::now());
    }

    /**
     * Check if code is disposable (ont-time).
     *
     * @return bool
     */
    public function isDisposable()
    {
        return $this->is_disposable;
    }

    /**
     * Check if code is expired.
     *
     * @return bool
     */
    public function isExpired()
    {
        return $this->expires_at ? Carbon::now()->gte($this->expires_at) : false;
    }

    /**
     * Check if code promo not commence.
     *
     * @return bool
     */
    public function notCommence()
    {
        return $this->commence_at ? Carbon::now()->lt($this->commence_at) : false;
    }

    /**
     * Check if code is Active.
     *
     * @return bool
     */
    public function isActive()
    {
        return $this->expires_at ? Carbon::now()->between($this->commence_at, $this->expires_at, false) : false;
    }

    /**
     * Check if code amount is over.
     *
     * @return bool
     */
    public function isOverAmount()
    {
        if (is_null($this->quantity)) {
            return false;
        }

        return $this->quantity <= 0;
    }

    public function type()
    {
        return $this->type == 'percentage' ? '%' : '$';
    }
}
