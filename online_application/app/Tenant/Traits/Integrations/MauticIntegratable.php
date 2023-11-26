<?php

namespace App\Tenant\Traits\Integrations;

use Illuminate\Http\Request;

/**
 * Shared Publish Process
 */
trait MauticIntegratable
{
    /**
     * Validate Mautic Integration Request
     */
    protected function validateMauticIntegration()
    {
        return [
            'title'     => 'required',
            'events'    => 'required',
            'url'       => 'required',
            'username'  => 'required',
            'password'  => 'required',
        ];
    }

    /**
     * Extract Matic Integration Custom Data
     * @return [type] [description]
     */
    protected function extractMauticCustomData(Request $request)
    {
        $data = [
            'url' => $request->url,
            'username' => $request->username,
            'password' => $request->password,
        ];

        if ($request->custom_field_names) {
            $data['custom_field_names'] = true;
            $data['mautic_field_pairs'] = json_decode($request->mautic_field_pairs, true);
        }

        return $data;
    }
}
