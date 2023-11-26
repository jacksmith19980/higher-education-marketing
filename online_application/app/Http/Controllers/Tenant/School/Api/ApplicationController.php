<?php

namespace App\Http\Controllers\Tenant\School\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\School\ApplicationsResource;
use App\School;
use App\Tenant\Models\Application;
use Illuminate\Http\Request;

class ApplicationController extends Controller
{
    public function index(Request $request)
    {
        $applications = Application::with(['sections', 'sections.fields'])->get();

        return ApplicationsResource::collection($applications);
    }

    public function show(School $school, Application $application, Request $request)
    {
        $application = $application->load(['sections', 'sections.fields']);

        return ApplicationsResource::collection($application);
    }
}
