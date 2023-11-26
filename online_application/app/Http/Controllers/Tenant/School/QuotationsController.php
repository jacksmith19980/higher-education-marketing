<?php

namespace App\Http\Controllers\Tenant\School;

use App\Events\Tenant\Quotation\QuotationEmailRequested;
use App\Helpers\Quotation\QuotationHelpers;
use App\Helpers\Quotation\StudentRegistrationQuotationHelper;
use App\Helpers\School\PromocodeHelpers;
use App\Http\Controllers\Controller;
use App\Repository\PromocodeRepository;
use App\School;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Promocode;
use App\Tenant\Models\Quotation;
use Auth;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\QueryException;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Redirector;
use Illuminate\Support\Str;
use Session;

class QuotationsController extends Controller
{
    private $promocodeRepository;

    public function __construct(PromocodeRepository $promocodeRepository)
    {
        $this->promocodeRepository = $promocodeRepository;
    }

    public function show($school, Quotation $quotation, Request $request)
    {
        Session::forget('child-impersonate');
        // Log the user out
        Auth::guard('student')->logout();

        $courses = Course::whereIn('id', $quotation->properties['courses'])->with('campuses', 'dates', 'addons')->get();

        $props = $quotation->properties;
        $quotation_courses = $props['courses'];

        // Return Campus with courses IN Quotation ONLY
        $campuses = Campus::whereIn('id', $props['campuses'])->with(
            ['courses' => function ($q) use ($quotation_courses) {
                $q->whereIn('course_id', $quotation_courses);
            }]
        )->get();

        $promos = false;
        if ($quotation->promocodeables()->count()) {
            $promos = true;
        }

        // Get All Steps (tabs)
        $tabs = $this->getQuotationTabs($props);

        // if No step redirect to the first tab's step
        $step = ($request->step) ? $request->step : reset($tabs)['step'];
        $firstStep = reset($tabs)['step'];

        // Clear Session if First Step
        if ($step == reset($tabs)['step']) {
        }

        // Get Previus and Next Steps
        $steps = $this->getPrevNextCurrentStep($step, $tabs);

        $cart = isset(
            $_COOKIE['cart_'.$quotation->slug]
        ) ? json_decode($_COOKIE['cart_'.$quotation->slug], true) : [];

        $price = [];

        // check if last Step Caluclate Price
        if ($step == 'quote') {
            // Calculate Price and Update Cart
//            list($price, $cart) = $this->calculatePrice($courses, $quotation, $cart);
            list($price, $cart) = QuotationHelpers::calculatePrice(
                $courses,
                $quotation,
                $cart,
                $this->promocodeRepository
            );
        }

        $accounts_booking = QuotationHelpers::accountsAllowedBooking($quotation->properties);

        $account_hide = (count($accounts_booking) == 1) ? true : false;

        return view(
            'front.quotations.show',
            compact(
                'quotation',
                'courses',
                'tabs',
                'step',
                'campuses',
                'steps',
                'cart',
                'price',
                'firstStep',
                'accounts_booking',
                'account_hide',
                'promos'
            )
        );
    }

    /**
     * Send Quotation Email
     *
     * @param string $school
     * @param Quotation $quotation
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function sendQuotaionEmail($school, Quotation $quotation, Request $request)
    {
        //dd($request);
        $request->validate([
        'title'      => 'required',
        'first_name' => 'required',
        'last_name'  => 'required',
        'phone'      => 'required',
        'email'      => 'required|email',
        ]);

        $data = [
        'title'       => $request->title,
        'first_name'  => $request->first_name,
        'last_name'   => $request->last_name,
        'phone'       => $request->phone,
        'email'       => $request->email,
        ];

        // dd($request);
        // Create Booking
        $registrationQuotationHelper = new StudentRegistrationQuotationHelper();
        $booking = $registrationQuotationHelper->createBooking($request->cart);
        $booking->details = json_encode(['user' => $data]);
        $booking->save();

        // Dispatch Quotation Request event
        event(new QuotationEmailRequested($data, $quotation, $booking));

        // Redirect to enquiry thank you page if exist
        if (
            isset($quotation->properties['eqnuiry_thank_you_page']) &&
            ! empty($quotation->properties['eqnuiry_thank_you_page'])
        ) {
            return redirect($quotation->properties['eqnuiry_thank_you_page']);
        }

        return redirect(route(
            'quotations.thank.you',
            ['school' => $request->school, 'quotation' => $quotation, 'booking' => $booking]
        ));
    }

    /**
     * Display Quotation Thank you page
     */
    public function quotationThankYouPage($school, Quotation $quotation, Booking $booking, Request $request)
    {
        return view('front.quotations.thank-you', compact('quotation', 'booking'));
    }

    protected function getUserId()
    {
        if ($user = Auth::guard('student')->user()) {
            $user_id = $user->id;
        } else {
            $user_id = Str::random(10);
        }

        return $user_id;
    }

    protected function setCoockie()
    {
        return [
        'campuses'        => [],
        'courses'         => [],
        'dates'           => [],
        'addons'          => [],
        'accomodations'   => [],
        'transfer'        => [],
        'misc'            => [],
        'price'           => [],
        ];
    }

    /**
     * Get Next, Previouse and Current Tabs
     *
     * @param string $current
     * @param array $tabs
     * @return array
     */
    protected function getPrevNextCurrentStep($current, $tabs)
    {
        $position = 0;
        $currentTab = [];
        foreach ($tabs as $key => $tab) {
            if ($tab['step'] == $current) {
                $position = $key;
                $currentTab = $tab;
            }
        }
        // If a position is found, splice the array.
        if ($position !== false) {
            $next = array_slice($tabs, ($position + 1));
        }

        if ($position !== false) {
            $prev = array_slice($tabs, ($position - 1));
        }

        return [
        'next'    => reset($next),
        'current' => $currentTab,
        'prev'    => reset($prev),
        ];

        return reset($next);
    }

    /**
     * Get Quotation Tabs
     *
     * @param array $props
     * @return array
     */
    protected function getQuotationTabs($props)
    {
        $tabs = [
            [
                'step'          => 'campus-course',
                'step_title'    => isset($props['campus_step']) ? $props['campus_step'] : $props['programs_step'],
                'title'         => isset($props['campus_title']) ? $props['campus_title'] : $props['programs_title'],
                'sub_title'     => isset($props['campus_sub_title']) ? $props['campus_sub_title'] : $props['programs_sub_title'],
                'error_message' => isset(
                    $props['campus_error_message']
                ) ? $props['campus_error_message'] : $props['programs_error_message'],
                'instructions'  => isset(
                    $props['campus_instructions']
                ) ? $props['campus_instructions'] : $props['programs_instructions'],
            ],

            [
                'step'          => 'date-addons',
                'step_title'    => isset($props['addons_step']) ? $props['addons_step'] : 'Date',
                'title'         => (isset($props['addons_title'])) ? $props['addons_title'] : 'Select Dates',
                'sub_title'     => (isset($props['addons_sub_title'])) ? $props['addons_sub_title'] : '',
                'error_message' => (isset(
                    $props['campus_error_message']
                )
                ) ? $props['campus_error_message'] : 'Please select at least one date for each course/campus',
                'instructions' => isset($props['addons_instructions']) ? $props['addons_instructions'] : null,
            ],

        ];
        if (isset($props['enable_accommodation'])) {
            $tabs[] = [
            'step'          => 'accomodations',
            'step_title'    => isset($props['accommodation_step']) ? $props['accommodation_step'] : 'Accomodations',
            'sub_title'     => isset($props['accommodation_sub_title']) ? $props['accommodation_sub_title'] : null,
            'title'         => isset(
                $props['accommodation_title']
            ) ? $props['accommodation_title'] : 'Select an accommodation for venue/course name',
            'error_message' => (
            isset($props['accommodation_error_message'])
            ) ? $props['accommodation_error_message'] : 'Please select at least one accommodation option',
            'instructions'  => isset($props['accommodation_instructions']) ? $props['accommodation_instructions'] : null,

            ];
        }
        if (isset($props['enable_transfer'])) {
            $tabs[] = [
            'step'          => 'transfer',
            'step_title'    => isset($props['transfer_step']) ? $props['transfer_step'] : 'Transfer',
            'sub_title'     => isset($props['transfer_sub_title']) ? $props['transfer_sub_title'] : null,
            'title'         => isset(
                $props['transfer_title']
            ) ? $props['transfer_title'] : 'Select a transfer option for venue/course name',
            'error_message' => (
            isset($props['transfer_error_message'])
            ) ? $props['transfer_error_message'] : 'Please select at least one transfer option',
            'instructions'  => isset($props['transfer_instructions']) ? $props['transfer_instructions'] : null,
            ];
        }
        if (isset($props['enable_misc'])) {
            $tabs[] = [
                'step'          => 'miscellaneous',
                'step_title'    => isset($props['mics_step']) ? $props['mics_step'] : 'Miscellaneous',
                'sub_title'     => isset($props['mics_sub_title']) ? $props['mics_sub_title'] : null,
                'title'         => isset(
                    $props['mics_title']
                ) ? $props['mics_title'] : 'Select a Miscellaneous option for venue/course name',
                'error_message' => (
                    isset($props['mics_error_message'])
                ) ? $props['mics_error_message'] : 'Please select at least one Miscellaneous option',
                'instructions' => isset($props['mics_instructions']) ? $props['mics_instructions'] : null,
            ];
        }
        $tabs[] = [
        'step'          => 'quote',
        'step_title'    => 'Quote',
        'title'         => 'Quote',
        'sub_title'     => null,
        'error_message' => '',
        ];

        return $tabs;
    }

    /**
     * Book a quotation
     */
    public function quotationBooking(Request $request, School $school, Quotation $quotation)
    {
        $this->logoutUser();

        /* if (!isset(QuotationHelpers::getQuotationsSettings()['agent_booking']) ||
            QuotationHelpers::getQuotationsSettings()['agent_booking'] == 'No'
        ) {
            $request['account'] = 'student';
        } */

        $registrationQuotationHelper = QuotationHelpers::factoryRegistrationQuotation($request->account);

        $request->validate($registrationQuotationHelper::validation());

        try {
            if ($user = $registrationQuotationHelper::user($request)) {
                $booking = $registrationQuotationHelper::createBooking($request->cart, $user->id);
                $this->registerDiscountToBooking($booking, $request->cart);

                return redirect(route($registrationQuotationHelper::redirectIfUserExist(), $school));
            }
        } catch (QueryException $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        $user = $registrationQuotationHelper::registerUser($request);

        $booking = $registrationQuotationHelper::createBooking($request->cart, $user->id, $user->role);

        $this->registerDiscountToBooking($booking, $request->cart);

        $route = $registrationQuotationHelper::afterRegistrationByRoleHandler($user, $request, $school);

        return redirect(route($route, $school));
    }

    /**
     * Login To user Account
     *
     * @param string $school
     * @param Quotation $quotation
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function quotationLogin($school, Quotation $quotation, Request $request)
    {
        $this->logoutUser();

        $request->validate([
            'account'       => 'required',
            'email'         => 'required|email',
            'password'      => 'required',
        ]);

        $registrationQuotationHelper = QuotationHelpers::factoryRegistrationQuotation($request->account);

        $credentials = $request->only('email', 'password');

        // Student or Parent login
        if ($request->account == 'student' || $request->account == 'parent') {
            // Login User
            if (Auth::guard('student')->attempt($credentials)) {
                $user = Auth::guard('student')->user();

                $booking = $registrationQuotationHelper::createBooking($request->cart, $user->id, $user->role);
                $this->registerDiscountToBooking($booking, $request->cart);

                if ($user->role == 'parent') {
                    // Route Parents to Parent's Home Page
                    return redirect(route('school.parents', $school));

                // Route Students to Student's Home Page
                } elseif ($user->role == 'student') {
                    return redirect(route('application.index', $school));
                }
                // invalid Credentials
            } else {
                return redirect()->back()->withInput($request->only('email'));
            }
        } elseif ($request->account == 'agent') {                       // Agent login
            if (Auth::guard('agent')->attempt($credentials)) {
                $user = Auth::guard('agent')->user();

                $booking = $registrationQuotationHelper::createBooking($request->cart, $user->id, $user->role);
                $this->registerDiscountToBooking($booking, $request->cart);

                return redirect(route($registrationQuotationHelper::redirectIfUserExist(), $school));
            } else {
                return redirect()->back()->withInput($request->only('email'));
            }
        }
    }

    private function calculateFeePrice($course)
    {
        if (isset($course['fee']) && is_numeric($course['fee'])) {
            return $course['fee'];
        }
    }

    public function recuperateMailQuotation($school, Booking $booking, $booking_user_id)
    {
        if ($booking->user_id != $booking_user_id) {
            abort(404);
        }

        $quotation = $booking->quotation;

        $student_information = json_decode($booking->details, true)['user'];

        $accounts_booking = QuotationHelpers::accountsAllowedBooking($booking->quotation->properties);

        if (count($accounts_booking) == 1) {
            $account_hide = true;
        } else {
            $account_hide = false;
        }

        return view(
            'front.quotations.recuperate.index',
            compact(
                'quotation',
                'accounts_booking',
                'account_hide',
                'booking',
                'student_information',
                'accounts_booking',
                'account_hide'
            )
        );
    }

    /**
     * Book a quotation after email received
     */
    public function quotationBookingByMail(Request $request, School $school, Quotation $quotation)
    {
        $this->logoutUser();

        $request->validate([
            'account'       => 'required',
            'email'         => 'required|email',
            'password'      => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        Auth::guard('student')->attempt($credentials);

        $booking = Booking::findOrFail($request->booking);

        $registrationQuotationHelper = QuotationHelpers::factoryRegistrationQuotation($request->account);

        $request->validate($registrationQuotationHelper::validation());

        try {
            if ($user = $registrationQuotationHelper::user($request)) {
                $booking->user_id = $user->id;
                $booking->save();

                return redirect(route($registrationQuotationHelper::redirectIfUserExist(), $school));
            }
        } catch (QueryException $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        $user = $registrationQuotationHelper::registerUser($request);

        $booking->user_id = $user->id;
        $booking->save();

        $route = $registrationQuotationHelper::afterRegistrationByRoleHandler($user, $request, $school);

        return redirect(route($route, $school));
    }

    protected function logoutUser(): void
    {
        Auth::guard('student')->logout();
        Session::forget('child-impersonate');
    }

    private function registerDiscountToBooking(Booking $booking, $cart)
    {
        $cartArray = json_decode($cart, true);
        if (isset($cartArray['discount']['code'])) {
            $promocodeModel = $this->promocodeRepository->byCode($cartArray['discount']['code'])->first();
            $promocodeModel->bookings()->save($booking);
        }
    }

    public function destroy($booking_id)
    {
        $booking = Booking::findOrFail($booking_id);
        if ($response = $booking->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['removedId' => $booking->id],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 404,
                    'response' => $response,
                ]
            );
        }
    }
}
