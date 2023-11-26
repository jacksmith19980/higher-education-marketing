<?php

namespace App\Tenant\Traits\Integrations;

use Illuminate\Http\Request;

/**
 * Shared Publish Process
 */
trait WebhookIntegratable
{
    /**
     * Validate Mautic Integration Request
     */
    protected function validateWebhookIntegration()
    {
        return [
            'title'     => 'required',
            'events'    => 'required',
            'method'    => 'required',
            'url'       => 'required',
        ];
    }

    /**
     * Extract Matic Integration Custom Data
     * @return [type] [description]
     */
    protected function extractWebhookCustomData(Request $request)
    {
        $data = [
                'url'       => $request->url,
                'method'    => $request->method,
        ];

        return $data;
    }
}
