<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Tenant\Models\Application;
use App\Http\Controllers\Controller;
use F9Web\ApiResponseHelpers;
use Illuminate\Http\JsonResponse;


class ApplicationsApiController extends Controller
{
    use ApiResponseHelpers;

    public function index(Request $request)
    {
        $applications = Application::all();
        return $this->respondWithSuccess(
            ['data' => $applications]
        );
    }

    public function update(Application $application, Request $request)
    {

    }
}
