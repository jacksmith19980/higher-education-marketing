<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Student;
use Carbon\Carbon;
use Hash;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;

class ResetPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset requests
    | and uses a simple trait to include this behavior. You're free to
    | explore this trait and override any methods you wish to tweak.
    |
    */

    use ResetsPasswords;

    /**
     * Where to redirect users after resetting their password.
     *
     * @var string
     */
    protected $redirectTo = '/home';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:student');
    }

    /**
     * Show Password Reset Form
     *
     * @param Request $request
     * @return void
     */
    public function showResetForm(Request $request)
    {

        // get Student by Provided Data
        $student = $this->getStudent($request->email, $request->token);
        // Wrong Email or Token
        if (! $student) {
            abort(404);
        }

        // token Has Expired
        $token = $student->token()->where('token', $request->token)->orderBy('created_at', 'desc')->first();
        if ($this->isExpired($token)) {
            $school = School::bySlug($request->school)->first();

            return redirect(route('school.forgot.password', $school))->with('error', 'Your request has expired, Please request a new link');
        }
        // VALID
        return view('front.auth.reset-password')->with([
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

    /**
     * Reset Student Password
     *
     * @return void
     */
    public function reset(Request $request)
    {
        $this->validate($request, [
            'password'  => 'required|confirmed|min:6',
        ]);

        $student = $this->getStudent($request->email, $request->token);
        // Wrong Email or Token
        if (! $student) {
            abort(404);
        }

        // token Has Expired
        $token = $student->token()->where('token', $request->token)->orderBy('created_at', 'desc')->first();
        if ($this->isExpired($token)) {
            $school = School::bySlug($request->school)->first();

            return redirect(route('school.forgot.password', $school))->with('error', 'Your request has expired, Please request a new link');
        }

        // New Password
        $student->password = Hash::make($request->password);

        // Delete Token
        $token->delete();

        //Save New Password
        $student->save();

        // Valid
        $school = School::bySlug($request->school)->first();

        $message = __('Your password changed successfully');
        return redirect(route('school.login', $school))->with('success', $message);
    }

    /**
     * Get Student by Password and Token
     *
     * @param [type] $email
     * @param [type] $token
     * @return void
     */
    protected function getStudent($email, $token)
    {
        $student = Student::byEmail($email)
            ->whereHas('token', function ($query) use ($token) {
                $query->where([
                    'token'     => $token,
                    'expired'   => false,
                ]);
            })
            ->first();

        return $student;
    }

    protected function isExpired($token)
    {
        $expiry = $token->created_at->addMinutes(30);
        // is Expired
        if ($expiry < Carbon::now()) {
            return true;
        }

        return false;
    }
}
