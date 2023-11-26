<?php

namespace App\Http\Controllers\Tenant\Auth\Instructors;

use App\Http\Controllers\Controller;
use App\Rules\School\InstructorExist;
use App\School;
use App\Tenant\Models\Instructor;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Str;
use Response;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest:instructor');
    }

    protected function validator(array $data)
    {
        return Validator::make($data, [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' =>  ['required', 'string', 'email', new InstructorExist()],
            'password' => 'required|string|min:6|confirmed',
        ]);
    }

    public function create(array $data)
    {
        return Instructor::create([
            'first_name'        => $data['first_name'],
            'last_name'         => $data['last_name'],
            'email'             => $data['email'],
            'password'          => Hash::make($data['password']),
            'activation_token'  => Str::random(255),
            'is_active'            => 0,
        ]);
    }

    public function showRegistrationForm(Request $request)
    {
        $school = School::bySlug($request->school)->first();

        return view('front.instructor.auth.register', compact('school'));
    }

    public function register(Request $request)
    {
        $school = School::bySlug($request->school)->first();

        $this->validator($request->all())->validate();

        // Create Instructor Account
        $instructor = $this->create($request->all());

        return redirect(route('school.instructor.login', $school))
            ->with(
                'success',
                'Your account has been created successfully, Please check your email address to activate your account'
            );
    }
}
