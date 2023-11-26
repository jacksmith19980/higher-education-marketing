<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Tenant\Models\Application;
use App\Tenant\Models\Integration;
use App\Tenant\Traits\Integrations\MauticIntegratable;
use App\Tenant\Traits\Integrations\HubspotIntegratable;
use App\Tenant\Traits\Integrations\WebhookIntegratable;
use App\Tenant\Traits\Integrations\CampusloginIntegratable;
use Illuminate\Http\Request;
use Response;

class IntegrationController extends Controller
{
    use MauticIntegratable;
    use HubspotIntegratable;
    use WebhookIntegratable;
    use CampusloginIntegratable;

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $application = Application::where('slug', $request->application)->with('sections')->first();
        $integration_type = $request->integration;
        $route = 'integration.store';
        $integration = new Integration;

        return view(
            'back.applications.integrations.'.$integration_type.'.form',
            compact('route', 'integration_type', 'application', 'integration')
        );
    }

    /**
     * Store Integration
     * @param Request $request [description]
     * @return [type]           [description]
     */
    public function store(Request $request)
    {
        // Call Validation Method based on the Integration Type
        $valudationMethod = 'validate'.ucwords($request->type).'Integration';

        // Validate Request
        $request->validate($this->{$valudationMethod}());

        // Extract Data
        $extractDataMethod = 'extract'.ucwords($request->type).'CustomData';
        $data = $this->{$extractDataMethod}($request);

        $integration = new Integration();
        $integration->title = $request->title;
        $integration->type = $request->type;
        $integration->events = $request->events;
        $integration->data = $data;

        $integration->application()->associate($request->application);

        if ($integration->save()) {
            $application = $request->application;

            $html = view('back.applications._partials.integration', compact('integration', 'application'))->render();

            return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => ['html' => $html],
            ]);
        } else {
            return Response::json([
                'status' => 400,
                'response' => 'fail',
                'extra' => [],
            ]);
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function edit(Integration $integration, Request $request)
    {
        $application = $integration->application;
        $integration_type = strtolower($integration->type);

        $route = 'integration.update';

        return view(
            'back.applications.integrations.'.strtolower($integration_type).'.form',
            compact('route', 'integration_type', 'application', 'integration')
        );
    }

    /**
     * Update Integration.
     *
     * @param \Illuminate\Http\Request $request
     * @param int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Integration $integration, Request $request)
    {
        // Call Validation Method based on the Integration Type
        $valudationMethod = 'validate'.ucwords($integration->type).'Integration';

        // Validate Request
        $request->validate($this->{$valudationMethod}());

        // Extract Data
        $extractDataMethod = 'extract'.ucwords($integration->type).'CustomData';
        $data = $this->{$extractDataMethod}($request);

        if ($integration->update([
            'title' => $request->title,
            'type' => $request->type,
            'events' => $request->events,
            'data' => $data,
        ])) {
            $application = $request->application;

            $html = view('back.applications._partials.integration', compact('integration', 'application'))->render();

            return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => ['html' => $html, 'integrationID' => $integration->id, 'title' => $integration->title],
            ]);
        } else {
            return Response::json([
                'status' => 404,
                'response' => 'Unexpected error',
            ]);
        }
    }

    /**
     * Delete Integration
     * @param  [Integration] $integration [description]
     * @return [JSON]                   [return rmeoved Integration Id to hide]
     */
    public function destroy(Integration $integration)
    {
        if ($response = optional($integration)->delete()) {
            return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => ['removedId' => $integration->id],
            ]);
        } else {
            return Response::json([
                'status' => 404,
                'response' => $response,
            ]);
        }
    }
}
