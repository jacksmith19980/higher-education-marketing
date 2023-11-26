<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class Call extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'call';
    }
}
