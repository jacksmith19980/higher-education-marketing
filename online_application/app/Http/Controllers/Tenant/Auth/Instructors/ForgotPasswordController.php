<?php

namespace App\Http\Controllers\Tenant\Auth\Instructors;

use App\Http\Controllers\Controller;
use App\Mail\Tenant\SendPasswordInstructorResetEmail;
use App\Tenant\Models\Instructor;
use App\Tenant\Models\InstructorToken;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;

class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest:instructor');
    }

    /**
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('front.instructor.auth.forgot-password');
    }

    /**
     * Send a reset link to the given user.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\RedirectResponse|\Illuminate\Http\JsonResponse
     */
    public function sendResetLinkEmail(Request $request)
    {
        $this->validateEmail($request);

        // We will send the password reset link to this user. Once we have attempted
        // to send the link, we will examine the response then see the message we
        // need to show to the user. Finally, we'll send out a proper response.

        // Send Reset Link
        if (! $instructor = $this->getInstructor($request->email)) {
            return $this->sendResetLinkFailedResponse($request, ' We can\'t find a user with that e-mail address.');
        }

        // Send Email
        $this->sendResetEmail($instructor);

        /*  return redirect( route('front.auth.forgot-password') )->with('success' , 'Please check your email for password reset link'); */
        $message = __('Please check your email for password reset link');

        return redirect()->back()->with('success', $message);
    }

    /**
     * Send Reset Email
     */
    protected function sendResetEmail($instructor)
    {
        $token = new InstructorToken();
        $token->token = Str::random(200);
        $token->expired = false;
        $instructor->token()->save($token);

        Mail::to($instructor->email)->send(new SendPasswordInstructorResetEmail($token->token, $instructor));
    }

    protected function getInstructor($email)
    {
        return Instructor::byEmail($email)->first();
    }
}
