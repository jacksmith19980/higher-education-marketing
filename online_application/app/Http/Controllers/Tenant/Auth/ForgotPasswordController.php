<?php

namespace App\Http\Controllers\Tenant\Auth;

use App\Http\Controllers\Controller;
use App\Mail\Tenant\SendPasswordResetEmail;
use App\Tenant\Models\Student;
use App\Tenant\Models\Token;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Mail;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

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
     * Display the form to request a password reset link.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        return view('front.auth.forgot-password');
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
        if (! $student = $this->getStudent($request->email)) {
            return $this->sendResetLinkFailedResponse($request, 'We can\'t find a user with that e-mail address.');
        }

        // Send Email
        $this->sendResetEmail($student);

        /*  return redirect( route('front.auth.forgot-password') )->with('success' , 'Please check your email for password reset link'); */
        $message = __('Please check your email for password reset link');

        return redirect()->back()->with('success', $message);
    }

    /**
     * Send Reset Email
     */
    protected function sendResetEmail($student)
    {
        $token = new Token();
        $token->token = Str::random(200);
        $token->expired = false;
        $student->token()->save($token);

        Mail::to($student->email)->send(new SendPasswordResetEmail($token->token, $student));
    }

    protected function getStudent($email)
    {
        return Student::byEmail($email)->first();
    }
}
