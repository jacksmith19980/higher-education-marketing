<?php

namespace App\Http\Controllers\Tenant\Auth\Instructors;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Instructor;
use Carbon\Carbon;
use Illuminate\Foundation\Auth\ResetsPasswords;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class ResetPasswordController extends Controller
{
    use ResetsPasswords;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest:instructor');
    }

    public function showResetForm(Request $request)
    {

        // get Student by Provided Data
        $instructor = $this->getInstructor($request->email, $request->token);
        // Wrong Email or Token
        if (! $instructor) {
            abort(404);
        }

        // token Has Expired
        $token = $instructor->token()->where('token', $request->token)->orderBy('created_at', 'desc')->first();
        if ($this->isExpired($token)) {
            $school = School::bySlug($request->school)->first();

            return redirect(route('school.instructor.forgot.password', $school))
                ->with('error', 'Your request has expired, Please request a new link');
        }
        // VALID
        return view('front.instructor.auth.reset-password')->with([
            'token' => $request->token,
            'email' => $request->email,
        ]);
    }

    public function reset(Request $request)
    {
        $this->validate($request, [
            'password'  => 'required|confirmed|min:6',
        ]);

        $instructor = $this->getInstructor($request->email, $request->token);
        // Wrong Email or Token
        if (! $instructor) {
            abort(404);
        }

        // token Has Expired
        $token = $instructor->token()->where('token', $request->token)->orderBy('created_at', 'desc')->first();
        if ($this->isExpired($token)) {
            $school = School::bySlug($request->school)->first();

            return redirect(route('school.instructor.forgot.password', $school))
                ->with('error', 'Your request has expired, Please request a new link');
        }

        // New Password
        $instructor->password = Hash::make($request->password);

        // Delete Token
        $token->delete();

        //Save New Password
        $instructor->save();

        // Valid
        $school = School::bySlug($request->school)->first();

        $message = __('Your password changed successfully');
        return redirect(route('school.instructor.login', $school))
            ->with('success', $message);
    }

    protected function getInstructor($email, $token)
    {
        $instructor = Instructor::byEmail($email)
            ->whereHas('token', function ($query) use ($token) {
                $query->where([
                    'token'     => $token,
                    'expired'   => false,
                ]);
            })
            ->first();

        return $instructor;
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
