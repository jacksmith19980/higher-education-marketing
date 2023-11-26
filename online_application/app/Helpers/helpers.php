<?php

use App\Tenant\Models\Setting;

if (! function_exists('money')) {
    function money($amount)
    {
        $settings = Setting::byGroup();
        $currency = $settings['school']['default_currency'] ?? 'CAD';

        return number_format($amount, 2) . ' ' .$currency;
    }
}
