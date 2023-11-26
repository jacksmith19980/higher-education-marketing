<?php

namespace App\Helpers\Plan;

class PlanHelpers
{
    public const REDIRECTIO_ON_FAIL = 'sales/contact';

    public static function getPlanFeatures()
    {
        return [
            'application'       => 'Application',
            'quote_builder'     => 'Quote Builder',
            'virtual_assistant' => 'Virtual Assistant',
            'sis'               => 'SIS',
            'agency'            => 'Agencies Portal',
            'e-signature'       => 'E-signature',
        ];
    }
}
