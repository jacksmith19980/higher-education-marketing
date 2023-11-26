<?php

namespace App\Exceptions;

use Exception;

class InvalidFiltersException extends Exception
{
    /**
     * @var string
     */
    protected $message = 'Promotion code is already used by current user.';

    /**
     * @var int
     */
    protected $code = 403;
}
