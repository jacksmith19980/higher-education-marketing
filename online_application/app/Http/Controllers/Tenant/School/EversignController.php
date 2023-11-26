<?php

namespace App\Http\Controllers\Tenant\School;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Log;

class EversignController extends Controller
{
    public function signed(Request $request)
    {
        Log::info($request);
    }
}
