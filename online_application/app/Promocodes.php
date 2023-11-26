<?php

namespace App;

use App\Exceptions\AlreadyUsedException;
use App\Exceptions\InvalidPromocodeException;
use App\Exceptions\UnauthenticatedException;
use App\Repository\PromocodeRepository;
use App\Tenant\Models\Promocode;
use Carbon\Carbon;

class Promocodes
{
    /**
     * Generated codes will be saved here
     * to be validated later.
     *
     * @var array
     */
    private $codes = [];

    /**
     * Length of code will be calculated from asterisks you have
     * set as mask in your config file.
     *
     * @var int
     */
    private $length;

    private $promocodeRepository;

    /**
     * Promocodes constructor.
     * @param PromocodeRepository $promocodeRepository
     */
    public function __construct(PromocodeRepository $promocodeRepository)
    {
        $this->promocodeRepository = $promocodeRepository;
        $this->codes = $this->promocodeRepository->pluck('code')->toArray();
        $this->length = substr_count(config('promocodes.mask'), '*');
    }

    /**
     * Generates promocodes as many as you wish.
     *
     * @param int $amount
     *
     * @param null $suggested_code
     * @return array
     */
    public function output($amount = 1, $suggested_code = null)
    {
        $collection = [];

        for ($i = 1; $i <= $amount; $i++) {
            $random = $this->generate($suggested_code);

            while (! $this->validate($collection, $random)) {
                $random = $this->generate();
            }

            array_push($collection, $random);
        }

        return $collection;
    }

    /**
     * Save promocodes into database
     * Successful insert returns generated promocodes
     * Fail will return empty collection.
     *
     * @param int $amount
     * @param null $reward
     * @param string $type
     * @param array $data
     * @param date|null $commence_at
     * @param int|null $expires_in
     * @param int|null $quantity
     *
     * @param bool $is_disposable
     * @param bool $is_automatic
     * @param null $suggested_code
     * @return \Illuminate\Support\Collection
     */
    public function create(
        $amount = 1,
        $reward = null,
        $type = null,
        array $data = [],
        $commence_at = null,
        $expires_in = null,
        $quantity = null,
        $is_disposable = false,
        $is_automatic = false,
        $suggested_code = null
    ) {
        $records = [];

        foreach ($this->output($amount, $suggested_code) as $code) {
            $records[] = [
                'code'          => $code,
                'reward'        => $reward,
                'type'          => $type,
                'data'          => json_encode($data),
                'commence_at'   => $commence_at ? $commence_at : Carbon::now(),
                'expires_at'    => $expires_in ?
                    Carbon::now()->addDays($expires_in) :
                    Carbon::now()->addDays(config('promocodes.expires_in')),
                'is_disposable' => $is_disposable,
                'is_automatic'  => $is_automatic,
                'quantity'      => $quantity,
            ];
        }

        if ($this->promocodeRepository->insert($records)) {
            return collect($records)->map(function ($record) {
                $record['data'] = json_decode($record['data'], true);

                return $record;
            });
        }

        return collect([]);
    }

    /**
     * Expire code as it won't usable anymore.
     *
     * @param string $code
     * @return bool
     * @throws InvalidPromocodeException
     */
    public function disable($code)
    {
        $promocode = $this->promocodeRepository->byCode($code)->first();

        if ($promocode === null) {
            throw new InvalidPromocodeException();
        }

        $promocode->expires_at = Carbon::now();
        $promocode->quantity = 0;

        return $this->promocodeRepository->save($promocode);
    }

    /**
     * Here will be generated single code using your parameters from config.
     *
     * @return string
     */
    private function generate($code = null)
    {
        if ($code != null) {
            return $code;
        }

        $characters = config('promocodes.characters');
        $mask = config('promocodes.mask');
        $promocode = '';
        $random = [];

        for ($i = 1; $i <= $this->length; $i++) {
            $character = $characters[rand(0, strlen($characters) - 1)];
            $random[] = $character;
        }

        shuffle($random);
        $length = count($random);

        $promocode .= $this->getPrefix();

        for ($i = 0; $i < $length; $i++) {
            $mask = preg_replace('/\*/', $random[$i], $mask, 1);
        }

        $promocode .= $mask;
        $promocode .= $this->getSuffix();

        return $promocode;
    }

    /**
     * Generate prefix with separator for promocode.
     *
     * @return string
     */
    private function getPrefix()
    {
        return (bool) config('promocodes.prefix')
            ? config('promocodes.prefix').config('promocodes.separator')
            : '';
    }

    /**
     * Generate suffix with separator for promocode.
     *
     * @return string
     */
    private function getSuffix()
    {
        return (bool) config('promocodes.suffix')
            ? config('promocodes.separator').config('promocodes.suffix')
            : '';
    }

    /**
     * Your code will be validated to be unique for one request.
     *
     * @param $collection
     * @param $new
     *
     * @return bool
     */
    private function validate($collection, $new)
    {
        return ! in_array($new, array_merge($collection, $this->codes));
    }

    /**
     * Check promocode in database if it is valid.
     *
     * @param string $code
     *
     * @return bool|Promocode
     * @throws InvalidPromocodeException
     */
    public function check($code)
    {
        $promocode = $this->promocodeRepository->byCode($code)->first();

        if ($promocode === null) {
            throw new InvalidPromocodeException();
        }

        if (
            $promocode->isExpired() ||
            $promocode->notCommence() || (
                $promocode->isDisposable() &&
                $this->promocodeRepository->promocodeusableExist($promocode)
            ) ||
            $promocode->isOverAmount()
        ) {
            return false;
        }

        return $promocode;
    }

    /**
     * Apply promocode to user that it's used from now.
     *
     * @param string $code
     *
     * @return bool|Promocode
     * @throws AlreadyUsedException
     * @throws UnauthenticatedException
     */
    public function apply($code)
    {
        if (! auth()->check()) {
            throw new UnauthenticatedException();
        }

        try {
            if ($promocode = $this->check($code)) {
                if ($this->isSecondUsageAttempt($promocode) && config('promocodes.one_per_user')) {
                    throw new AlreadyUsedException();
                }

                $promocode->users()->attach(auth()->id(), [
                    'promocode_id' => $promocode->id,
                    'used_at' => Carbon::now(),
                ]);

                if (! is_null($promocode->quantity)) {
                    $promocode->quantity -= 1;
                    $this->promocodeRepository->save($promocode);
                }

                return $this->promocodeRepository->load($promocode, 'users');
            }
        } catch (InvalidPromocodeException $exception) {
            //
        }

        return false;
    }

    /**
     * Save one-time use promocodes into database
     * Successful insert returns generated promocodes
     * Fail will return empty collection.
     *
     * @param int $amount
     * @param null $reward
     * @param string $type
     * @param array $data
     * @param date|null $commence_at
     * @param int|null $expires_in
     * @param int|null $quantity
     *
     * @param null $suggested_code
     * @return \Illuminate\Support\Collection
     */
    public function createDisposable(
        $amount = 1,
        $reward = null,
        $type = null,
        array $data = [],
        $commence_at = null,
        $expires_in = null,
        $quantity = null,
        $suggested_code = null
    ) {
        return $this->create(
            $amount,
            $reward,
            $type,
            $data,
            $commence_at,
            $expires_in,
            $quantity,
            true,
            $suggested_code
        );
    }

    /**
     * Check if user is trying to apply code again.
     *
     * @param Promocode $promocode
     *
     * @return bool
     */
    public function isSecondUsageAttempt(Promocode $promocode)
    {
        return $this->promocodeRepository->isSecondUsageAttempt($promocode);
    }
}
