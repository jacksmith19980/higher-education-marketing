<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Rules\School\ReCaptcha;
use App\School;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use Auth;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Http\Request;

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
    protected $redirectTo = '/applications';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:student')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        $params = $request->query();
        $school = School::bySlug($request->school)->first();
        $params['school'] = $request->school;
        $settings = Setting::byGroup();
        $layout = isset($settings['auth']['login_type']) ? strtolower($settings['auth']['login_type']) : 'basic';
        return view('front.auth.login', compact('params', 'school' , 'layout'));

    }

    public function login(Request $request)
    {
        $rules = [
            'email'         => 'required|email',
            'password'      => 'required|min:6',
        ];

        $settings = Setting::byGroup();
        if (isset($settings['auth']['enable_recaptcha']) && $settings['auth']['enable_recaptcha'] == 'Yes') {
            $rules['g-recaptcha-response'] = [new ReCaptcha, 'required'];
        }
        $this->validate($request, $rules);

        // Attempt to log the user in
        if (Auth::guard('student')->attempt(['email' => $request->email, 'password' => $request->password], $request->remember)) {
            $school = School::bySlug($request->school)->first();
            $role = Auth::guard('student')->user()->role;

            // Update Booking
            if ($request->has(['booking', 'user'])) {
                $this->updateBooking(Auth::guard('student')->user(), $request);
            }

            // Redirect parents to parents Home page
            if ($role == 'parent') {
                return redirect(route('school.parents', $school));
            }

            // if successful, then redirect to their intended location
            return redirect()->intended(route('school.home', $school));
        }

        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()
            ->withInput($request->only('email', 'remember'))
            ->withErrors(['email' => 'Check your email and password or activate your account']);
    }

    /**
     * Update User Booking if exist
     *
     * @param Student $user
     * @param Request $request
     * @return void
     */
    protected function updateBooking($user, Request $request)
    {
        if (! $request->has(['booking', 'user'])) {
            return true;
        }
        $booking = Booking::where([
            'id'        => $request->booking,
            'user_id'   => $request->user,
        ])->first();

        if ($booking) {
            $booking->user_id = $user->id;
            $booking->save();
        }
    }

    public function logout(Request $request)
    {
        Auth::guard('student')->logout();

        $school = School::bySlug($request->school)->first();

        return redirect(route('school.home', $school));
    }
}
