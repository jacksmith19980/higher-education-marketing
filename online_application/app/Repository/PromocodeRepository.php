<?php

namespace App\Repository;

use App\Tenant\Models\Promocode;
use Carbon\Carbon;

class PromocodeRepository
{
    private $query;

    public function all()
    {
        return Promocode::all();
    }

    public function types()
    {
        return ['percentage' => 'Percentage', 'flat' => 'Flat'];
    }

    public function pluck($field)
    {
        return Promocode::pluck('code');
    }

    public function insert(array $records)
    {
        return Promocode::insert($records);
    }

    public function byCode(string $code)
    {
        $this->query = Promocode::byCode($code);

        return $this;
    }

    public function save($promocode)
    {
        return $promocode->save();
    }

    public function first()
    {
        return $this->query->first();
    }

    public function promoable($promocode)
    {
        return $promocode->promoable();
    }

    public function promoableExist($promocode)
    {
        return $promocode->promoable()->exists();
    }

    public function promocodeusable($promocode)
    {
        return $promocode->promocodeusable();
    }

    public function promocodeusableExist($promocode)
    {
        return $promocode->promocodeusable()->exists();
    }

    public function isSecondUsageAttempt(Promocode $promocode)
    {
        return $promocode->users()->where('user_id', auth()->id())->exists();
    }

    public function load(Promocode $promocode, string $string)
    {
        return $promocode->load($string);
    }

    public function getGlobalPromocodes()
    {
        return Promocode::whereDate('commence_at', '<', Carbon::now())
            ->whereDate('expires_at', '>', Carbon::now())
            ->doesnthave('quotations')
            ->get();
    }
}
