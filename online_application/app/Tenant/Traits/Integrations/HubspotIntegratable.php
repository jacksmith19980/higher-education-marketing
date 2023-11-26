<?php

namespace App\Tenant\Traits\Integrations;

use Illuminate\Http\Request;

/**
 * Shared Publish Process
 */
trait HubspotIntegratable
{
    /**
     * Validate Hubspot Integration Request
     */
    protected function validateHubspotIntegration()
    {
        return [
            'api_key' => 'required'
        ];
    }

    /**
     * Extract Matic Integration Custom Data
     * @return [type] [description]
     */
    protected function extractHubspotCustomData(Request $request)
    {
        $data = [
            'api_key' => $request->api_key
        ];
        if ($request->custom_field_names) {
            $data['custom_field_names'] = true;
            $data['mautic_field_pairs'] = json_decode($request->mautic_field_pairs, true);
        }
        return $data;
    }
}
