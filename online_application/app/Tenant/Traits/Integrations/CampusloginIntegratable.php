<?php

namespace App\Tenant\Traits\Integrations;

use Illuminate\Http\Request;

/**
 * Shared Publish Process
 */
trait CampusloginIntegratable
{
    /**
     * Validate Mautic Integration Request
     */
    protected function validateCampusloginIntegration()
    {
        return [
            'title'         => 'required',
            'url'           => 'required',
            'ORGID'         => 'required',
            'MailListID'    => 'required',
        ];
    }

    /**
     * Extract Matic Integration Custom Data
     * @return [type] [description]
     */
    protected function extractCampusloginCustomData(Request $request)
    {
        $data = [
                'url'           => $request->url,
                'ORGID'         => $request->ORGID,
                'MailListID'    => $request->MailListID,
                'sync_all_steps'=> $request->sync_all_steps,
        ];

        if ($request->custom_field_names) {
            $data['custom_field_names'] = true;
            $data['mautic_field_pairs'] = json_decode($request->mautic_field_pairs, true);
        }
        return $data;
    }
}
