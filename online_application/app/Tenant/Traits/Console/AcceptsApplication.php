<?php

namespace App\Tenant\Traits\Console;

use Symfony\Component\Console\Input\InputOption;

trait AcceptsApplication
{
    protected function getOptions()
    {
        return array_merge(
            parent::getOptions(), [
                ['application', null, InputOption::VALUE_IS_ARRAY | InputOption::VALUE_OPTIONAL, '', null],
            ]
        );
    }
}
