<?php

namespace App\Http\Controllers;

use App\Plan;
use Illuminate\Http\Request;

class SalesContactController extends Controller
{
    public function index(Request $request)
    {
        return view('back.salesTeam.index');
    }
}
