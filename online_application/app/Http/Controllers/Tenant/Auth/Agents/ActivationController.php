<?php

namespace App\Http\Controllers\Tenant\Auth\Agents;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Agent;
use Auth;
use Illuminate\Http\Request;

class ActivationController extends Controller
{
    public function activate(Request $request)
    {
        $school = School::bySlug($request->school)->firstOrFail();

        // Get Agent By Email and Token
        $agent = Agent::byActivationColumns($request->token, $request->email)->first();
        if (!$agent) {
            return redirect(route('school.agent.home', ['school' => $school]))->with('success', __('Your account has been
            activated successfully!'));
        }

        $agent->update([
            'activation_token' => null,
            'active' => true,
        ]);
        // Login the User and redirect to home page
        //Auth::guard('agent')->login($agent);
        return redirect(route('school.agent.home', ['school' => $school]));
    }
}
