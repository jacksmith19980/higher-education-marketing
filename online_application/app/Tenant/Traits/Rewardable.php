<?php

namespace App\Tenant\Traits;

use App\Exceptions\AlreadyUsedException;
use App\Exceptions\InvalidPromocodeException;
use App\Promocodes;
use App\Tenant\Models\Promocode;
use Carbon\Carbon;

trait Rewardable
{
    public $repository;
//    /**
//     * Get the promocodes that are related to user.
//     *
//     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
//     */
//    public function promocodes()
//    {
//        return $this->belongsToMany(Promocode::class, config('promocodes.relation_table'))
//            ->withPivot('used_at');
//    }

    public function setRepository($respository)
    {
        $this->repository = $respository;

        return $this;
    }

    /**
     * Get all of the promocode for the student.
     */
    public function promocodes()
    {
        return $this->morphToMany(Promocode::class, 'promocodeusable');
    }

//    /**
//     * Apply promocode to user and get callback.
//     *
//     * @param string $code
//     * @param null|\Closure $callback
//     *
//     * @return null|Promocode
//     * @throws AlreadyUsedException
//     */
//    public function applyCode($code, $callback = null)
//    {
//        try {
//            $promocodeBusiness = new Promocodes($this->repository);
//            if ($promocode = $promocodeBusiness->check($code)) {
//                if ($promocode->users()->wherePivot(config('promocodes.related_pivot_key'), $this->id)->exists()) {
//                    throw new AlreadyUsedException();
//                }
//
//                $promocode->users()->attach($this->id, [
//                    config('promocodes.foreign_pivot_key') => $promocode->id,
//                    'used_at' => Carbon::now(),
//                ]);
//
//                if (!is_null($promocode->quantity)) {
//                    $promocode->quantity -= 1;
//                    $promocode->save();
//                }
//
//                $promocode->load('users');
//
//                if (is_callable($callback)) {
//                    $callback($promocode);
//                }
//
//                return $promocode;
//            }
//        } catch (InvalidPromocodeException $exception) {
//            //
//        }
//
//        if (is_callable($callback)) {
//            $callback(null);
//        }
//
//        return null;
//    }
//
//    /**
//     * Redeem promocode to user and get callback.
//     *
//     * @param string $code
//     * @param null|\Closure $callback
//     *
//     * @return null|Promocode
//     * @throws AlreadyUsedException
//     */
//    public function redeemCode($code, $callback = null)
//    {
//        return $this->applyCode($code, $callback);
//    }
}
