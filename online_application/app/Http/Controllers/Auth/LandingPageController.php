<?php

namespace App\Http\Controllers\Auth;

use DB;
use Mail;
use Http;
use App\Plan;
use App\Team;
use App\User;
use App\School;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Tenant\Models\Setting;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use App\Events\Tenant\TenantWasCreated;
use App\Events\Tenant\UsersWereInvited;
use App\Services\Payment\StripeService;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\Rules\Password;
use App\Http\Controllers\SchoolController;
use Illuminate\Foundation\Auth\RegistersUsers;
use Illuminate\Validation\ValidationException;
use App\Mail\Tenant\NewFreeTrialNotificationMail;
use App\Repositories\Interfaces\SchoolRepositoryInterface;

class LandingPageController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Landing Page Controller
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
    protected $redirectTo = '/thankyou';

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    protected $schoolRepository;

    /**
     * Get a validator for an incoming registration request.
     *
     * @param  array  $data
     * @return \Illuminate\Contracts\Validation\Validator
     */
    protected function validator(array $data)
    {
        return Validator::make($data, [
            'name' => 'required|string|max:255',
            'lastname' => 'required|string|max:255',
            'email' => 'required|string|email|max:255|unique:users',
            'password'   => ['required', 'confirmed', Password::min(8)
                ->letters() // Require at least one letter...
                ->mixedCase() // Require at least one uppercase and one lowercase letter...
                ->numbers() // Require at least one number...
                ->symbols() // Require at least one symbol...
                //->uncompromised() // has not been compromised in a public password data breach leak
            ],
            'institutionname'=> 'required|string|max:255',
            'school_type'=> 'required|string|max:255',
            'school_url' => 'required|string|max:255',
            'plan' => 'required|integer',
        ]);
    }

    /**
     * Create a new user instance after a valid registration.
     *
     * @param \Illuminate\Http\Request $data
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\Foundation\Application|\Illuminate\Http\RedirectResponse|\Illuminate\View\View
     * @throws \Illuminate\Validation\ValidationException
     */
    protected function create(Request $data)
    {
        $payment_description = $this->processPlanPayment($data);

        $user = $this->createtUser($data);

        //return $user;
        if (! empty($user)) {
            // Log the User In
            $this->guard()->login($user);

            return $this->createSchool($data, $user, $payment_description);
        } else {
            exit();
            die();
        }
    }

    public function payment(Request $request)
    {
        $request->validate([
            'name'              => 'required|string|max:255',
            'lastname'          => 'required|string|max:255',
            'email'             => 'required|string|email|max:255|unique:users',
            'password'   => ['required', 'confirmed', Password::min(8)
                ->letters() // Require at least one letter...
                ->mixedCase() // Require at least one uppercase and one lowercase letter...
                ->numbers() // Require at least one number...
                ->symbols() // Require at least one symbol...
                //->uncompromised() // has not been compromised in a public password data breach leak
            ],
            'institutionname'   => 'required|string|max:255',
            'school_type'       => 'required|string|max:255',
            'school_url'        => 'required|string|max:255',
            'plan'              => 'required|integer',
        ]);

        $plan_obj = Plan::findOrFail($request->plan);

        if ($plan_obj->price == 0) {
            $user = $this->createtUser($request);

            if (! empty($user)) {
                // Log the User In
                $this->guard()->login($user);

                return $this->createSchool($request, $user);
            } else {
                exit();
                die();
            }
        }

        return view('back.auth.payment.stripe')
            ->with(array_merge(
                $request->only('name', 'lastname', 'email', 'password', 'institutionname', 'school_type', 'school_url', 'plan'),
                ['plan_obj' => $plan_obj]
            ));
    }
    /**
     * Save User/School Relation
     */
    protected function assignUsersToSchool($school, $user)
    {
        $school->users()->save($user);

        $admins = explode("," , env('SUPER_ADMINS'));
        foreach ($admins as $admin) {
            $admin = User::find(trim($admin));
            if($admin){
                $school->users()->save($admin);
            }
        }
        return $school;
    }
    public function createSchool($data, $user, $payment_description = null)
    {
        $school = School::create([
            'name'        => $data['institutionname'],
            'slug'        => Str::slug($data['institutionname']),
        ]);

        // Assign User and HEM to the school
        $this->assignUsersToSchool($school, $user);

        $settings = [
            'website'     => $data['school_url'],
            'locale'      => 'en',
            'school_type' => $data['school_type'],
        ];
        // dispatch Event
        $team = Team::create([
            'title'         => $this->uniqueId(),
            'plan_id'       => $data->plan,
            'properties'    => ['payment_description' => $payment_description],
        ]);

        $user->team_id = $team->id;
        $team->user_id = $user->id;
        $team->save();
        $user->save();

        event(new TenantWasCreated($school));

        session()->put('tenant', $school->uuid);

        if (isset($settings)) {
            foreach ($settings as $slug => $value) {
                $setting = Setting::firstOrNew(['slug' => $slug]);
                if ($value) {
                    $setting->data = (is_array($value)) ? array_filter($value) : $value;
                    $setting->group = 'school';
                } else {
                    $setting->data = '';
                }
                $setting->save();
            }
        }

        Mail::to([
            'ptaza@higher-education-marketing.com', 'mattalah@higher-education-marketing.com',
            'scross@higher-education-marketing.com',
            ])->send(new NewFreeTrialNotificationMail($data, $user, $school));

		return $this->processFormCurl($data, $user, $school);
    }

    protected function uniqueId($l = 8)
    {
        return substr(md5(uniqid(mt_rand(), true)), 0, $l);
    }

    /**
     * Show the application registration form.
     *
     * @return \Illuminate\Http\Response
     */
    public function showLandingPage()
    {
        $plans = Plan::where('is_active', 1)->get();
        session()->forget('tenant');

        return view('back.auth.landingpage', compact('plans'));
    }

    public function processRegisteredUserToHubSpot($data, $user, $school)
    {
        $apiKey = env('HUBSPOT_API_KEY');

        $properties = [];

        $properties = [
            'properties' => [
                'firstname'         => isset($data['name']) ? $data['name'] : null,
                'lastname'          => isset($data['lastname']) ? $data['lastname'] : null,
                'email'             => isset($data['email']) ? $data['email'] : null,
                'phone'             => isset($data['phone'])?  $data['phone'] : null,
                'country'           => isset($data['country']) ? $data['country'] : null,
                'jobtitle'          => isset($data['jobtitle']) ? $data['jobtitle'] : null,
                'school_name__c'    => isset($data['institutionname']) ? $data['institutionname'] : null,
                'school_type__c'    => isset($data['school_type']) ? $data['school_type'] : null,
                'website'           => isset($data['school_url']) ? $data['school_url'] : null,
                'product'           => 'HEM-SP Free Trial',
                'sql'               => 'Yes',
                'lifecyclestage'    => 'salesqualifiedlead',
            ]
        ];


        $response = Http::post('https://api.hubapi.com/crm/v3/objects/contacts?hapikey='.$apiKey, $properties)->json();

        return view('back.thankyou')->with('school', $school);
    }

	public function processFormCurl($data, $user, $school)
    {
        $apiKey = env('HUBSPOT_API_KEY');

        $properties = [];

        $properties = [
            'properties' => [
                'firstname'         => isset($data['name']) ? $data['name'] : null,
                'lastname'          => isset($data['lastname']) ? $data['lastname'] : null,
                'email'             => isset($data['email']) ? $data['email'] : null,
                'phone'             => isset($data['phone'])?  $data['phone'] : null,
                'country'           => isset($data['country']) ? $data['country'] : null,
                'jobtitle'          => isset($data['jobtitle']) ? $data['jobtitle'] : null,
                'school_name__c'    => isset($data['institutionname']) ? $data['institutionname'] : null,
                'school_type__c'    => isset($data['school_type']) ? $data['school_type'] : null,
                'website'           => isset($data['school_url']) ? $data['school_url'] : null,
                'product'           => 'HEM-SP Free Trial',
                'sql'               => 'Yes',
                'lifecyclestage'    => 'salesqualifiedlead',
            ]
        ];

		$site       = 'https://application.crmforschools.net';
		$endpoint 	= 'https://forms.hubspot.com/uploads/form/v2/381672/55d7dcd6-9042-4729-8152-d3a983909044';//replace the values in this URL with your portal ID and your form GUID
		$hubspotutk = isset($_COOKIE['hubspotutk']) ? $_COOKIE['hubspotutk'] : null ; //grab the cookie from the visitors browser.

		$ip_addr    = $_SERVER['REMOTE_ADDR']; //IP address too.
		if($_POST)
		{
			//Process a new form submission in HubSpot in order to create a new Contact.
			$hs_context = array(
				'hutk' => $hubspotutk,
				'ipAddress' => $ip_addr,
				'pageUrl' => $site,
				'pageName' => 'Free Trial'
			);

			$product = 'HEM-SP Free Trial';
			$sql = 'Yes';
			$lifecycle = 'salesqualifiedlead';

			$hs_context_json = json_encode($hs_context);

			//Need to populate these variable with values from the form.
			//Hubspot Fields -> Form Fields
			$str_post = "firstname=" . urlencode($_POST['name'])
				. "&lastname=" . urlencode($_POST['lastname'])
				. "&email=" . urlencode($_POST['email'])
				. "&phone=" . urlencode($_POST['phone'])
				. "&country=" . urlencode($_POST['country'])
				. "&jobtitle=" . urlencode($_POST['job_title'])
				. "&school_name__c=" . urlencode($_POST['institutionname'])
				. "&school_type__c=" . urlencode($_POST['school_type'])
				. "&website" . urlencode($_POST['school_url'])
				. "&product=" . urlencode($product)
				. "&sql=" . urlencode($sql)
				. "&lifecyclestage=" . urlencode($lifecycle)
				. "&hs_context=" . urlencode($hs_context_json); //Leave this one be

			$ch = @curl_init();
			@curl_setopt($ch, CURLOPT_POST, true);
			@curl_setopt($ch, CURLOPT_POSTFIELDS, $str_post);
			@curl_setopt($ch, CURLOPT_URL, $endpoint);
			@curl_setopt($ch, CURLOPT_HTTPHEADER, array(
				'Content-Type: application/x-www-form-urlencoded'
			));
			@curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
			$response    = @curl_exec($ch); //Log the response from HubSpot as needed.
			$status_code = @curl_getinfo($ch, CURLINFO_HTTP_CODE); //Log the response status code
			@curl_close($ch);
			//echo $status_code . " " . $response;
		}

		return view('back.thankyou')->with('school', $school);
    }

    protected function subscriptionPayment($data): array
    {
        $data->validate([
            'card_no'           => 'required',
            'ccExpiryMonth'     => 'required',
            'ccExpiryYear'      => 'required',
            'cvvNumber'         => 'required',
        ]);

        $plan = Plan::findOrFail($data->plan);
        $stripe_service = new StripeService(env('stripe_secret_key', ''));

        $customer_stripe = $stripe_service->addCustomer([
            'name'          => $data['name'].' '.$data['lastname'],
            'email'         => $data['email'],
            'description'   => $plan->id,
            'card'          => $stripe_service->createToken(
                $data->only(['card_no', 'ccExpiryMonth', 'ccExpiryYear', 'cvvNumber'])
            ),
        ]);

        $plan_stripe = $stripe_service->addPlan(
            $plan,
            env('currency_code', ''),
            'month'
        );

        return $stripe_service->addSubscription($customer_stripe, $plan_stripe);
    }

    /**
     * @param $data
     * @return array
     */
    protected function processPlanPayment($data)
    {
        try {
            $subscription = $this->subscriptionPayment($data);
        } catch (\Exception $exception) {
            throw ValidationException::withMessages(['card_error' => $exception->getMessage()]);
        }

        return $subscription;
    }

    /**
     * @param $data
     */
    protected function feePayment($data): void
    {
        $stripe_service = new StripeService(env('stripe_secret_key', ''));

        $payment_details = [
            'amount' => $data->amount,
            'currency_code' => env('currency_code', ''),
            'item_name' => 'Fee', //TODO Change this
            'token' => $stripe_service->createToken(
                $data->only(['card_no', 'ccExpiryMonth', 'ccExpiryYear', 'cvvNumber'])
            ),
        ];
    }

    /**
     * @param \Illuminate\Http\Request $data
     * @return mixed
     */
    protected function createtUser(Request $data)
    {
        $name = $data['name'].' '.$data['lastname'];
        $role = 'Admin';
        $user = User::create([
            'name'      => $name,
            'phone'     => $data['phone'],
            'position'  => $data['job_title'],
            'email'     => $data['email'],
            'password'  => Hash::make($data['password']),
            'is_active' => 1,
        ]);
        $this->addDefaultRole($user);
        return $user;
    }

    protected function addDefaultRole($user)
    {
        $user->assignRole('Admin');
    }
}
