<?php

namespace App\Http\Controllers\Tenant\School\Api;

use App\Http\Controllers\Controller;
use App\Http\Resources\School\ProgramsResource;
use App\Tenant\Models\Program;
use Illuminate\Http\Request;

class ProgramController extends Controller
{
    public function index(Request $request)
    {
        return ProgramsResource::collection(Program::all());
    }
}
