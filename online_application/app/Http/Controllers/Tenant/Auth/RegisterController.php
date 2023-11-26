<?php

namespace App\Http\Controllers\Tenant\Auth;

use Str;
use Auth;
use Session;
use App\School;
use Illuminate\Http\Request;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use App\Rules\School\ReCaptcha;
use App\Rules\School\StudentExist;
use App\Tenant\Models\CustomField;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use App\Events\Tenant\Parent\ParentRegistred;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Events\Tenant\Student\StudentRegistred;

//use App\Events\Tenant\Tracker\TrackEvent;

class RegisterController extends Controller
{
    /*

    |--------------------------------------------------------------------------

    | Register Controller

    |--------------------------------------------------------------------------

    |

    | This controller handles the registration of new users as well as their

    | validation and creation. By default this controller uses a trait to

    | provide this functionality without requiring any additional code.

    |

    */
    use RegistersUsers;

    /**
     * Where to redirect users after registration.
     *
     * @var string
     */
    //protected $redirectTo = '/home';
    protected $redirectTo = '/';

    /**School Settings */
    protected $settings;

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
     * Create a new user instance after a valid registration.
     *
     * @param  array  $data
     * @return \App\User
     */
    public function create(array $data)
    {

        return Student::create([
            'first_name'    => Str::title($data['first_name']),
            'last_name'     => Str::title($data['last_name']),
            'phone'         => isset($data['phone']) ?
                    [
                        'phone' => $data['phone'],
                        'countrycode_phone' => isset($data['countrycode_phone']) ? $data['countrycode_phone']: '' ,
                        'phonefield_phone' => isset($data['phonefield_phone']) ? $data['phonefield_phone'] : '',
                        'country_code_phone' => isset($data['country_code_phone']) ? $data['country_code_phone'] : ''
                    ] : '',
            'email'         => $data['email'],
            'role'          => $data['role'],
            'address'       => isset($data['address'])? $data['address'] : null,
            'country'       => isset($data['country'])? $data['country'] : null,
            'city'          => isset($data['city'])? $data['city'] : null,
            'postal_code'   => isset($data['postal_code'])? $data['postal_code'] : null,
            'consent'       => isset($data['consent']) ? true : false,
            'parent_id'     => (isset($data['parent_id'])) ? $data['parent_id'] : null,
            'password'      => Hash::make($data['password']),
            'params'        => isset($data['params'])? $data['params'] : [],
        ]);
    }

    /**
     * Show the application registration form.
     */
    public function showRegistrationForm(Request $request, $school)
    {
        if (!ctype_digit((string) $request->user) && $request->user != null) {
            return redirect(route('quotations.recuperate.email', [
                'school'    => $school,
                'booking'   => $request->booking,
                'user'      => $request->user,
            ]));
        }

        $params = $request->query();

        if(Session::get('url_params')){
            $params += Session::get('url_params');
        }
        $params['school'] = $request->school;
        $school = School::bySlug($request->school)->first();
        $settings = Setting::byGroup();
        $layout = isset($settings['auth']['login_type']) ? strtolower($settings['auth']['login_type']) : 'basic';

        $customFields = CustomField::where('properties','students')
        ->where('for_forms' , true)->get()->toArray();

        return view('front.auth.register', compact('params', 'school','layout' , 'customFields'));
    }

    /**
     * Handle a registration request for the application.
     */
    public function register(Request $request)
    {

        $settings = Setting::byGroup();
        $rules = [
            'first_name'            => 'required|string|max:255',
            'last_name'             => 'required|string|max:255',
            'role'                  => 'required|string|max:255',
            'email'                 => ['required','email:filter', new StudentExist()],
            'password'              => 'required|string|min:6|confirmed',
        ];

        if (isset($settings['auth']['phone_register']) && $settings['auth']['phone_register'] == 'Yes') {
            $rules['phone'] = 'required|string|max:255';
            /* $rules['countrycode_phone'] = 'required|string|max:255';
            $rules['phonefield_phone'] = 'required|string|max:255';
            $rules['country_code_phone'] = 'required|string|max:255'; */
        }

        if(isset($settings['auth']['show_campus']) && $settings['auth']['show_campus'] == 'Yes')
        {
            $rules['campus'] = 'required|string|max:255';
        }

        if(isset($settings['auth']['show_program']) && $settings['auth']['show_program'] == 'Yes')
        {
            $rules['program'] = 'required|string|max:255';
        }

        if(isset($settings['auth']['show_country']) && $settings['auth']['show_country'] == 'Yes')
        {
            $rules['country'] = 'required|string|max:255';
        }

        if (isset($settings['auth']['enable_recaptcha']) && $settings['auth']['enable_recaptcha'] == 'Yes') {
            $rules['g-recaptcha-response'] = [new ReCaptcha(), 'required'];
        }

        $request->validate($rules);

        $data = $this->extractStudentData($request);

        $student = $this->create($data);

        if($request->has('campus')){
            if($campus = Campus::where('slug' , strtolower($request->campus))->orWhere('id' , $request->campus )->first()){
                $student->campuses()->attach($campus->id);
            }
        }
        // Get School
        $school = School::bySlug($request->school)->firstOrFail();

        if ($student->role == 'student') {
            // Dispatch Student Registered Event
            event(new StudentRegistred($student, $school));
        } else {
            // Dispatch Student Registered Event
            event(new ParentRegistred($student, $school, 'Parent', $request));
        }

        $intendedUrl = Session::get('intended_url');

        // Attempt to log the user in
        if (Auth::guard('student')->attempt(['email' => $request->email, 'password' => $request->password], false)) {
            if ($student->role == 'student') {

                if($intendedUrl){

                    return Redirect::to($intendedUrl);

                }else{
                    // if successful, then redirect to their intended location
                   return redirect()->intended(route('school.home', $school));
                }

            } elseif ($student->role == 'parent') {

                return redirect(route('school.parents', $school));

            }
        }
    }


    protected function extractStudentData($request)
    {
        $basicFields = ['first_name', 'last_name', 'email', 'password' , 'country' , 'role' , 'phone' , 'countrycode_phone','phonefield_phone','country_code_phone'];
        $data = $request->only($basicFields);

        $params = $request->except(array_merge($basicFields ,['_token', 'password_confirmation' , 'school']));

        $data['params'] = $params;
        return $data;
    }

}
