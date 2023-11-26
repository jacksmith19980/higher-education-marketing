<?php

namespace App\Http\Controllers;

use App\School;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    public function switch(School $school)
    {
        session()->put('tenant', $school->uuid);
        session()->forget('settings-'.session('tenant'));
        return redirect('/dashboard');
    }
}
