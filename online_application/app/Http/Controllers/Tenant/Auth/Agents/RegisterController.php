<?php

namespace App\Http\Controllers\Tenant\Auth\Agents;

use App\Rules\School\ReCaptcha;
use Response;
use App\School;
use Illuminate\Support\Str;
use App\Tenant\Models\Agent;
use Illuminate\Http\Request;
use App\Tenant\Models\Agency;
use App\Rules\School\AgentExist;
use App\Tenant\Models\Application;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Helpers\School\AgencyHelpers;
use Illuminate\Support\Facades\Validator;
use App\Rules\Tenant\Agencies\UniqueAgency;
use App\Events\Tenant\Agent\AgentRegistered;
use App\Events\Tenant\Agency\AgencyIsCreated;
use Illuminate\Foundation\Auth\RegistersUsers;
use App\Tenant\Models\Setting;
use Session;

class RegisterController extends Controller
{
    use RegistersUsers;

    protected $redirectTo = '/home';

    public function __construct()
    {
        $this->middleware('guest:agent');
    }

    protected function validator(array $data)
    {
        $settings = Setting::byGroup('auth');

        $rules = [
            'first_name' => 'required|string|max:255',
            'last_name' => 'required|string|max:255',
            'email' =>  ['required', 'string', 'email', new AgentExist()],
            'password' => 'required|string|min:6|confirmed',
        ];

        if (isset($settings['auth']['enable_recaptcha']) && $settings['auth']['enable_recaptcha'] == 'Yes') {
            $rules['g-recaptcha-response'] = ['required', new ReCaptcha()];
        }

        return Validator::make($data, $rules);
    }

    public function create(array $data)
    {
        return Agent::create([
            'first_name'        => $data['first_name'],
            'last_name'         => $data['last_name'],
            'is_admin'          => $data['is_admin'],
            'email'             => $data['email'],
            'agency_id'         => $data['agency_id'],
            'password'          => Hash::make($data['password']),
            'activation_token'  => Str::random(255),
        ]);
    }

    public function showRegistrationForm(Request $request)
    {
        $agencies = Agency::pluck('name', 'email')->toArray();

        if ($request->has('company')) {
            $agencies = [$request->company => 'Company Name'];
        } else {
            $remoteAgencies = AgencyHelpers::getRemoteAgencies();

            if (isset($remoteAgencies['companies'])) {
                foreach ($remoteAgencies['companies'] as $agency) {
                    if (isset($agency['fields']['core']['companyemail']['value'])) {
                        $agencies[$agency['fields']['core']['companyemail']['value']] = $agency['fields']['core']['companyname']['value'];
                    }
                }
            }
        }

        $params = $request->query();

        if (Session::get('url_params')) {
            $params += Session::get('url_params');
        }

        $params['school'] = $request->school;

        return view('front.agent.auth.register', compact('agencies','params'));
    }

    public function register(Request $request)
    {
        $school = School::bySlug($request->school)->first();

        $this->validator($request->all())->validate();

        if ($request->has('company')) {
            $agency = Agency::where('email', $request->agency)->firstOrFail();
            $request->request->add(['is_admin' => false]);
        } else {
            $agency = $this->createAgency($request);
            $request->request->add(['is_admin' => true]);
        }

        $request->request->add(['agency_id' => $agency->id]);

        $agent = $this->create($request->all());

        event(new AgentRegistered($agent, $school, $agency));

        $applications = Application::byObject('agency')->where('properties' , 'LIKE','%"show_in":"registration"%')->get();

        if ($applications->count()) {
            return redirect()->route('school.agent.register.step2', [
                'school'=>$school,
                'agency' => $agency,
                'agentId' => $agent->id,
                'agentEmail' => $agent->email
            ])
            ->with('success', __('Please complete your registration form'));
        }

        return redirect()->route('school.agent.login', $school)
            ->with('success', __('Your account has been created successfully, Please check your email address to activate your account'));
    }

    public function showRegistrationApplications(School $school ,Agency $agency , Request $request)
    {
        $agent = $agency->agents()->first();
        $agentId = $agent->id;
        $agentEmail = $agent->email;

        $applications = Application::byObject('agency')
            ->where('properties', 'LIKE','%"show_in":"registration"%')
            ->get();

        return view('front.agent.auth.register-step2', compact('school', 'agency', 'applications', 'agentId', 'agentEmail'));
    }

    public function registrationApplicationSubmit(School $school ,Agency $agency , Request $request)
    {
        $applicationIds = $request->application_id;
        $submissions = [];
        foreach ($applicationIds as $applicationId) {
            $application = Application::find($applicationId);
            if ($application) {
                $request->merge(['application_id' => $applicationId]);
                $submissions[] = app('App\Http\Controllers\Tenant\School\Agent\AgencySubmissionsController')->submit($school , $agency ,
                $application ,$request , false);
            }
        }

        if (count($submissions)) {
            $settings = Setting::byGroup('agencies');

            if ($settings['agencies']['automatically_approve_agencies'] == 'No') {
                $message = 'Thank you for application, We will review your request and contact you soon.';
            } else {
                $message = 'Your account has been created successfully, Please check your email address to activate your account';
            }

            return redirect(route('school.agent.login', $school))->with('success', __($message));
        }

        return redirect(route('school.agent.register.step2', ['school'=> $school, 'agency' => $agency]))->with('error', __('Something went wrong, Please try again'));
    }

    protected function createAgency(Request $request)
    {
        $settings = session('settings-'.session('tenant'));

        $apporved = false;
        if (isset($settings['agencies']['automatically_approve_agencies']) && $settings['agencies']['automatically_approve_agencies'] == 'Yes') {
            $apporved = true;
        }

        $request->validate([
            'agency_name'  => ['required', 'max:255', new UniqueAgency('name')],
            'agency_email' => ['required', 'email', new UniqueAgency('email')],
        ]);

        $agency = Agency::create([
            'name'          => $request->agency_name,
            'email'         => $request->agency_email,
            'approved'      => $apporved,
        ]);

        event(new AgencyIsCreated($agency));

        return $agency;
    }
}
