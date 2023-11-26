<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;

class DirectMessage extends Facade
{
    protected static function getFacadeAccessor()
    {
        return 'direct_message';
    }
}
