<?php

namespace App\Helpers\Quotation;

use App\Events\Tenant\Parent\ParentRegistred;
use App\Events\Tenant\Student\StudentRegistred;
use App\Helpers\Quotation\Interfaces\RegistrationQuotationHelperInterface;
use App\Tenant\Models\Student;
use Auth;
use Illuminate\Support\Str;

class StudentRegistrationQuotationHelper extends RegistrationQuotationHelper implements
    RegistrationQuotationHelperInterface
{
    public static function validation()
    {
        return [
            'first_name'    => 'required',
            'last_name'     => 'required',
            'account'       => 'required',
            'email'         => 'required|email',
            'password'      => 'required|confirmed',
        ];
    }

    public static function registerUser($request)
    {
        if (! $password = $request->password) {
            $password = Str::random(7);
        }

        $data = [
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'email'                 => $request->email,
            'role'                  => $request->role,
            'account'               => $request->account,
            'consent'               => $request->consent,
            'password'              => $password,
            'password_confirmation' => $password,
        ];

        return app(\App\Http\Controllers\Tenant\Auth\RegisterController::class)->create($data);
    }

    public static function user($request)
    {
        return Student::where('email', $request->email)->first();
    }

    public static function afterRegistrationByRoleHandler($user, $request, $school)
    {
        switch ($user->role) {
            case 'parent':
                event(new ParentRegistred($user, $school, self::getContactType($request->role), $request));

                if (Auth::guard('student')->loginUsingId($user->id)) {
                    return 'school.parents';
                }
                break;
            case 'student':
                event(new StudentRegistred($user, $school, self::getContactType($request->role)));

                if (Auth::guard('student')->loginUsingId($user->id)) {
                    return 'school.home';
                }
                break;
        }
    }

    protected static function getContactType($role = 'student')
    {
        if ($role == 'student') {
            return 'Student';
        }
        if ($role == 'parent') {
            return 'Parent';
        }

        return 'Lead';
    }

    public static function redirectIfUserExist()
    {
        return 'school.parents';
    }
}
