<?php

namespace App\Http\Controllers\Tenant\Auth\Agents;

use App\Events\Tenant\Agent\ActivationEmailRequested;
use App\Http\Controllers\Controller;
use App\Rules\School\AgentExist;
use App\Rules\School\AgentIsActivated;
use App\Rules\School\AgentIsNotExist;
use App\Rules\School\ReCaptcha;
use App\School;
use App\Tenant\Models\Agent;
use App\Tenant\Models\Setting;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest:agent')->except('logout');
    }

    public function showLoginForm()
    {
        return view('front.agent.auth.login');
    }

    public function showResendActivation()
    {
        return view('front.agent.auth.resend-activation');
    }

    public function resendActivation(Request $request)
    {
        $this->validate($request, [
            $this->username() => ['required', 'string', new AgentIsNotExist()],
        ]);

        $agent = Agent::where('email', $request->email)->firstOrFail();

        $school = School::bySlug($request->school)->first();

        event(new ActivationEmailRequested($agent, $school));

        return redirect(route('school.agent.login', $school))
            ->with('success', 'The activation email sent successfully. Please, check your inbox folder or your spam folder');
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);

        if (Auth::guard('agent')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $school = School::bySlug($request->school)->first();

            return redirect(route('school.agent.home', $school));
        }

        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    protected function validateLogin(Request $request)
    {
        $settings = Setting::byGroup('auth');

        $rules = [
            $this->username() => ['required', 'string', new AgentIsActivated()],
            'password' => ['required', 'string'],
        ];

        if (isset($settings['auth']['enable_recaptcha']) && $settings['auth']['enable_recaptcha'] == 'Yes') {
            $rules['g-recaptcha-response'] = ['required', new ReCaptcha()];
        }

        $this->validate($request, $rules);
    }

    public function logout(Request $request)
    {
        Auth::guard('agent')->logout();

        $school = School::bySlug($request->school)->first();

        return redirect(route('school.agent.login', $school));
    }
}
