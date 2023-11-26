<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Sign extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'sign';
    }
}
