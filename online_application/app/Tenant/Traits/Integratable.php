<?php

namespace App\Tenant\Traits;

use App\Tenant\Models\Plugin;
use App\Tenant\Models\Setting;
use App\Integrations\Mautic\Mautic;
use App\Integrations\Webhook\Webhook;
use App\Integrations\BasicIntegration as Integration;

trait Integratable
{
    public function getIntegration($integration = null, $properties)
    {
        if(!$integration){
            return null;
        }
        $integration = 'App\\Integrations\\' .ucwords($integration).'\\' . ucwords($integration) ;
        return new $integration($properties, false);
    }


    public function inetgration()
    {
        $plugin = Plugin::where('type', 'crm')->where('published', true)->first();
        if ($plugin) {
            $basicintegration = new Integration;
            $integration = $basicintegration->getIntegration($plugin);
            return $integration;
        }

        $settings = Setting::byGroup('integrations');
        if (count($settings)) {
            $settings = $settings['integrations'];
            if (isset($settings['integration_mautic'])) {
                return new Mautic(
                    $settings['mautic_username'],
                    $settings['mautic_password'],
                    $settings['mautic_url']
                );
            } elseif (isset($settings['integration_webhook'])) {
                return new Webhook($settings['webhook_method'], $settings['webhook_url']);
            } else {
                return false;
            }
        }
        return false;
    }
}
