<?php

namespace App\Http\Controllers\Tenant\School\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\School\CampusesResource;
use App\Tenant\Models\Campus;
use Illuminate\Http\Request;

class CampusController extends Controller
{
    /**
     * List All Campuses
     *
     * @param Request $request
     * @return void
     */
    public function index(Request $request)
    {
        return CampusesResource::collection(Campus::all());
    }
}
