<?php

namespace App\Http\Controllers\Auth;

use Auth;
use App\User;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Rules\School\UserIsActivated;
use Illuminate\Http\RedirectResponse;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

class LoginController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */
    use AuthenticatesUsers;

    /**
     * Where to redirect users after login.
     *
     * @var string
     */
    // /protected $redirectTo = '/schools';
    protected $redirectTo = 'home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout', 'activate');
    }

    /**
     * Show the application's login form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLoginForm()
    {
        session()->forget('tenant');

        return view('back.auth.login');
    }

    public function activate(Request $request)
    {
        session()->forget('tenant');
        if (!$request->filled('token') || !$request->filled('email')) {
            return abort(404);
        }

        $user = User::byActivationMethods($request->email, $request->token)->firstOr(function(){
            return null;
        });

        if(is_null($user)){
            return redirect(route($this->redirectTo));
        }


        if (!$user->is_active) {
            $user->is_active = true;
            $user->activation_token = null;
            $user->save();
        }
        Auth::guard('web')->onceUsingId($user->id);
        return redirect(route($this->redirectTo));
    }

    /**
     * Validate the user login request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return void
     */
    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => ['required', 'string', new UserIsActivated()],
            'password'          => 'required|string',
        ]);
    }

    /**
     * The user has been authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  mixed  $user
     * @return mixed
     */
    protected function authenticated(Request $request, $user)
    {
        return redirect(route($this->redirectTo));
    }
}
