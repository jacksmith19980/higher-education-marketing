<?php

namespace App\Http\Controllers;

use Auth;
use Illuminate\Http\Request;

class CoreController extends Controller
{
    public function home(Request $request)
    {
        if (Auth::guard('web')->user()->hasRole('Super Admin') || $this->activePlan()) {
            return view('home');
        }
        return redirect()->route('sales.contact.index');
    }

    public function activePlan()
    {
        if($team = Auth::guard('web')->user()->team){
            return $team->plan_id;
        }
        return 5;
        //return ! is_null(Auth::guard('web')->user()->team->plan_id);
    }
}
