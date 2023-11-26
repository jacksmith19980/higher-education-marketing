<?php

namespace App\Http\Controllers\Tenant\Auth\Instructors;

use Auth;
use Session;
use App\School;

use Illuminate\Http\Request;
use App\Tenant\Models\Instructor;
use App\Http\Controllers\Controller;
use App\Rules\School\InstructorExist;
use App\Rules\School\InstructorIsActivated;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Events\Tenant\Agent\ActivationEmailRequested;

class LoginController extends Controller
{
    use AuthenticatesUsers;

    public function __construct()
    {
        $this->middleware('guest:instructor')->except('logout');
    }

    public function showLoginForm(Request $request)
    {
        Session::forget('impersonate-instructor');
        $school = School::bySlug($request->school)->first();
        return view('front.instructor.auth.login', compact('school'));
    }

    public function showResendActivation()
    {
        return view('front.instructor.auth.resend-activation');
    }

    public function resendActivation(Request $request)
    {
        $this->validate($request, [
            $this->username() => ['required', 'string', new InstructorExist()],
        ]);
        $instructor = Instructor::where('email', $request->email)->firstOrFail();
        if ($instructor) {
            // Get School
            $school = School::bySlug($request->school)->first();
            // dispatch Activation Email Requested Event
            event(new ActivationEmailRequested($instructor, $school));
            // if successful, then redirect to their intended location
            return redirect(route('school.instructor.login', $school))
                ->with(
                    'success',
                    'The activation email sent successfuly. Please, check your inbox folder or your spam folder'
                );
        }
    }

    public function login(Request $request)
    {
        $this->validateLogin($request);
        // Get the Instructor By Email and Username

        // Attempt to log the user in
        if (
            Auth::guard('instructor')->attempt([
                'email' => $request->email,
                'password' => $request->password,
            ], $request->remember)
        ) {
            // Get School
            $school = School::bySlug($request->school)->first();
            // if successful, then redirect to their intended location
            return redirect(route('school.instructor.home', $school));
        }

        // if unsuccessful, then redirect back to the login with the form data
        return redirect()->back()->withInput($request->only('email', 'remember'));
    }

    protected function validateLogin(Request $request)
    {
        $this->validate($request, [
            $this->username() => ['required', 'string', new InstructorIsActivated()],
            'password' => 'required|string',
          ]);
    }

    public function logout(Request $request)
    {
        Auth::guard('instructor')->logout();
        $school = School::bySlug($request->school)->first();

        return redirect(route('school.instructor.login', $school));
    }
}
