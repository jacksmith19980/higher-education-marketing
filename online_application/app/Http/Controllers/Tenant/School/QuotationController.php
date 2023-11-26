<?php

namespace App\Http\Controllers\Tenant\School;

use App\Events\Tenant\Parent\ParentRegistred;
use App\Events\Tenant\Quotation\QuotationEmailRequested;
use App\Events\Tenant\Student\StudentRegistred;
use App\Helpers\Quotation\QuotationHelpers;
use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Quotation;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use App\Tenant\Traits\Quotation\GetData;
use Auth;
use Hash;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Str;
use Response;
use Session;

class QuotationController extends Controller
{
    use GetData;

    public function show($school, Quotation $quotation)
    {

        // Stop Impersonating
        Session::forget('child-impersonate');

        $userId = session()->get('invoice_user_id', Str::random(5));
        $blocks = QuotationHelpers::getDefaultFlowOrder();

        return view('front.quotation.show', compact('quotation', 'userId', 'blocks'));
    }

    /**
     * Global Ajax Request
     *
     * @param Request $request
     * @return void
     */
    public function update(Request $request)
    {
        if (! $request->action || ! method_exists($this, $request->action)) {
            return Response::json([
                'status' => 404,
                'response' => 'Fail',
                'extra' => ['html' => 'Not found'],
            ]);
        }
        // Special Treatment for GetPrice /// Need to be refactored
        if ($request->action == 'getPrice' || $request->action == 'calculatePrice') {
            return $this->{$request->action}($request);
        } else {
            return $this->{$request->action}($request->payload);
        }
    }

    /**
     * Get Gourses
     *
     * @param [type] $payload
     * @return void
     */
    protected function getData($payload)
    {
        $quotaion = Quotation::find($payload['quotation']);
        $method = 'get'.ucwords($payload['get']).'By'.ucwords($payload['by']);

        return $this->{$method}($quotaion, $payload['values']);
    }

    /**
     * Calculate Booking Price
     *
     * @param Request $request
     * @return void
     */
    protected function calculatePrice(Request $request)
    {
        $accommodationPrice = 0;
        $transferPrice = 0;
        // We need the quotation to get the specific pricing information
        $quotation = Quotation::find($request->quotation);

        //Courses
        $courses = $this->extractUserSelection($request);

        // Caluculate Courses and Addons Prices
        $coursesPrice = $this->calculateCoursesPrice($courses, $quotation);

        //Calculate Accommodation Price
        $accommodationPrice = $this->calculateAccommodationPrice($request, $quotation);

        //Calculate Transfer Price
        if ($request->transfer) {
            $transferPrice = $this->calculateTransferPrice($request, $quotation);
        }

        $bookingDetails = [
            'courses_addons' => $coursesPrice,
            'campus'         => $request->campus,
            'accommodation'  => $accommodationPrice,
            'transfer'       => $transferPrice,
            'totalPrice'     => $coursesPrice['totalCoursesPrice'] + $transferPrice['transferPrice'] + $accommodationPrice['accommodationPrice'],
        ];
        // Save the Invoice
        $booking = Booking::create([
            'quotation_id'  => $quotation->id,
            'user_id'       => $request->user_id,
            'invoice'       => $bookingDetails,
        ]);

        $html = view('front.quotation.show-price', compact('booking', 'quotation'))->render();

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    /**
     * Extract user selection
     * ( user may select Multiple programs, Multiple dates , Multiple Activities ,and Multiple Transfer )
     *
     * @param Request $request
     * @return void
     */
    protected function extractUserSelection(Request $request)
    {
        $courses = [];

        foreach ($request->program as $key => $program) {
            if (isset($request->date[$program])) {
                $courses[$program] = [
                    'course'    => $program,
                    'dates'     => $request->date[$program],
                    'addons'     => isset($request->addons[$program]) ? $request->addons[$program] : null,
                ];
            }
        }

        return $courses;
    }

    /**
     * Calculate Transfer Options Price
     *
     * @param Request $request
     * @param Quotation $quotaion
     * @return void
     */
    protected function calculateTransferPrice(Request $request, Quotation $quotaion)
    {
        $transferDetails = [];
        $transferDetails['transferPrice'] = 0;

        foreach ($request->transfer as $transfer) {
            if ($transfer !== null) {
                $transferDetails['transfers'][$transfer] = [

                    'transferTitle' => $quotaion->properties['transfer_options'][$transfer],
                    'transferPrice' => $quotaion->properties['transfer_options_price'][$transfer],
                ];
                $transferDetails['transferPrice'] += $transferDetails['transfers'][$transfer]['transferPrice'];
            }
        }

        return $transferDetails;
    }

    /**
     * Calculate Accommodation Options Price
     *
     * @param Request $request
     * @param Quotation $quotaion
     * @return void
     */
    protected function calculateAccommodationPrice(Request $request, Quotation $quotaion)
    {
        $accommodationferDetails = [];
        $accommodationferDetails['accommodationPrice'] = 0;
        if (isset($request->accommodation) && ! empty($request->accommodation)) {
            foreach ($request->accommodation as $accommodation) {
                if ($accommodation !== null) {
                    $accommodationferDetails['accommodations'][$accommodation] = [

                        'accommodationTitle' => $quotaion->properties['accommodation_options'][$accommodation],
                        'accommodationPrice' => $quotaion->properties['accommodation_options_price'][$accommodation],
                    ];

                    //@TODO
                    // Check Pricing Option if price is per week we need to calculate the weeks
                    $accommodationferDetails['accommodationPrice'] += $accommodationferDetails['accommodations'][$accommodation]['accommodationPrice'];
                }
            }
        }

        return $accommodationferDetails;
    }

    /**
     * Calculate Courses Price
     *
     * @param [type] $courses
     * @param Quotation $quotaion
     * @return void
     */
    protected function calculateCoursesPrice($courses, Quotation $quotaion)
    {
        $courseDetails = [];
        $totalPrice = 0;

        foreach ($courses as $id => $selectedCourseDetails) {
            // Get Course
            $course = Course::find($id);

            $campuses = $course->campuses()->pluck('title')->toArray();

            //check date type to calculate the price
            switch ($course->properties['dates_type']) {
                case 'specific-dates':
                    $courseDetails = $this->getSpecificDatesCoursePrice($selectedCourseDetails, $course, $quotaion);
                    break;
            }

            $courseInformation[$id] = [
                'course'                     => $course->title,
                'campsues'                   => $campuses,
                'totalWeeks'                 => $courseDetails['totalWeeks'],
                'selectedDates'              => $courseDetails['selectedDates'],
                'course_materials_fee'       => $course->properties['course_materials_fee'],
                'course_registeration_fee'   => $course->properties['course_registeration_fee'],
                'addons'                     => isset($courseDetails['addons']) ? $courseDetails['addons'] : null,
                'addonsPrice'                => isset($courseDetails['addonsPrice']) ? $courseDetails['addonsPrice'] : null,
                'price'                      => $courseDetails['totalPrice'],
            ];

            $totalPrice += $courseInformation[$id]['price'];
        }

        return [
            'courses'              => $courseInformation,
            'totalCoursesPrice'    => $totalPrice,
        ];
    }

    /**
     * Calculate the price for courses with specific start and end dates
     *
     * @param [type] $selectedCourse
     * @param Course $course
     * @param Quotation $quotation
     * @return void
     */
    protected function getSpecificDatesCoursePrice($selectedCourse, Course $course, Quotation $quotation)
    {
        $coursesPrice = 0;

        $dates = Arr::flatten($selectedCourse['dates']);

        foreach ($dates as $date) {
            $startdate = explode(':', $date)[0];
            $key = array_search($startdate, $course->properties['start_date']);
            $datePrice = $course->properties['date_price'][$key];

            $coursesPrice += $datePrice;
        }

        // get number of selected Period
        $selectedPeriods = count($dates);
        //Total Week (selected Period * number of weeks per period)
        $totalWeeks = $selectedPeriods * $course->properties['number_of_weeks'];

        $data = [
            'totalWeeks' => $totalWeeks,
            'selectedDates' => $dates,
            'coursesPrice' => $coursesPrice,
        ];

        // Calculate addons Price
        if ($selectedCourse['addons']) {
            $addonsDetails = $this->calculateProgramAddonsPrice($selectedCourse['addons'], $totalWeeks, $course);

            $data['addons'] = $addonsDetails['addons'];
            $data['addonsPrice'] = $addonsDetails['totalPrice'];
        } else {
            $addonsDetails['totalPrice'] = 0;
        }

        // Calculate Total Price
        $data['totalPrice'] = ($coursesPrice + $addonsDetails['totalPrice']);

        return $data;
    }

    /**
     * Calculate Addons Price
     *
     * @param [type] $addons
     * @param [type] $weeks
     * @param Course $program
     * @return void
     */
    // @toDo Switch to Porgram Model
    protected function calculateProgramAddonsPrice($addons, $weeks, Course $program)
    {
        $totalPrice = 0;
        $addonsList = [];
        $programAddons = $program->properties['addons'];

        foreach ($addons as $date => $addon) {
            $addon = reset($addon);

            if (isset($program->properties['addons']['addon_options_category'][$addon])) {
                $addonCategory = $program->properties['addons']['addon_options_category'][$addon];

                // Calculate Price for Addon Type ACTIVITY
                if ($addonCategory == 'activity') {
                    $tmp = (int) isset($addonsList[$addon]['week']) ? $addonsList[$addon]['week'] : 0;
                    // Calculate Price for Addon Type Material
                    $addonTitle = $program->properties['addons']['addon_options'][$addon];
                    $addonsList[$addon]['title'] = $addonTitle;
                    $addonsList[$addon]['week'] = $tmp + $this->getNumberofWeeks($this->getNumberOfDays($date));
                    $addonsList[$addon]['price'] = $addonsList[$addon]['week'] * $program->properties['addons']['addon_options_price'][$addon];

                    // Increment Total Price
                    $totalPrice += $this->getNumberofWeeks($this->getNumberOfDays($date)) * $program->properties['addons']['addon_options_price'][$addon];
                } elseif ($addonCategory == 'material') {
                }
            }
        }

        return [
            'addons'        => $addonsList,
            'totalPrice'    => $totalPrice,
        ];
    }

    /**
     * Calculate Activities Price
     *
     * @param [type] $selectedActivities
     * @param [type] $weeks
     * @param Quotation $quotation
     * @return void
     */
    protected function calculateActivitiesPrice($selectedActivities, $weeks, Quotation $quotation)
    {
        $totalPrice = 0;
        $activities = [];

        foreach ($selectedActivities as $key => $activity) {
            if (isset($quotation->properties['misc_options'][$activity])) {
                $activities[$activity] = [
                    'title' => $quotation->properties['misc_options'][$activity],
                ];

                if ($quotation->properties['mics_cost_template'] == 'weekly') {
                    // Numbr of weeks for Each Activity this will cause problems if number of weeks is different
                    $activityWeeks = $weeks / (count($selectedActivities));

                    $activityPrice = $quotation->properties['misc_options_price'][$activity] * $activityWeeks;
                    $totalPrice += $activityPrice;

                    $activities[$activity]['weeks'] = $activityWeeks;
                    $activities[$activity]['price'] = $activityPrice;
                } elseif ($quotation->properties['mics_cost_template'] == 'fixed-price') {
                    $activityPrice = $quotation->properties['misc_options_price'];
                    $totalPrice += $activityPrice;
                    $activities[$activity]['price'] = $activityPrice;
                }
            }
        }

        return [
            'activities' => $activities,
            'totalPrice' => $totalPrice,
        ];
    }

    /**
     * Calculate Course Price
     *
     * @param Request $request
     * @return void
     */
    public function getPrice(Request $request)
    {

        // Get Number of Days
        $days = strtotime($request->end_date) - strtotime($request->start_date);
        $numberOfDays = ($days / (60 * 60 * 24)) + 1;
        $numberofWeeks = self::getNumberofWeeks($numberOfDays);

        $course = Course::find($request->courses);
        $settings = Setting::byGroup('quotation');

        // Get Course Price
        $coursePrice = self::getCoursePrice([
            'numberOfDays' => $numberOfDays,
            'courseID' => $request->courses,
            'startDate' => $request->start_date,
            'endDate' => $request->end_date,
        ], $course, $settings);

        // Accommodation Price
        if ($accommodationPrice = $request->accommodation) {
            $accommodationTemp = explode('_', $accommodationPrice);
            $accommodationPrice = $accommodationTemp[1];
            $accommodationPrice = $accommodationPrice * $numberofWeeks;
            if (! $accommodation = $settings['quotation']['accomodation_options'][$accommodationTemp[0]]) {
                $accommodation = null;
            }
        }

        // Transfer Price
        if ($transferPrice = $request->transfer) {
            $transferTemp = explode('_', $transferPrice);
            $transferPrice = $transferTemp[1];
            if (! $transfer = $settings['quotation']['transfer_options'][$transferTemp[0]]) {
                $transfer = null;
            }
        }

        // Registeration Fees
        if (! $registerationFees = $course->properties['course_registeration_fee']) {
            $registerationFees = $settings['quotation']['global_registeration_fees'];
        }

        // Material Fees
        if (! $materialFees = $course->properties['course_materials_fee']) {
            $materialFees = $settings['quotation']['global_materials_fees'];
        }

        // Get Total Price
        if ($totalPrice = $coursePrice + $accommodationPrice + $transferPrice + $materialFees + $registerationFees) {
            $viewParams = [
                'totalPrice' => $totalPrice,
                'coursePrice' => $coursePrice,
                'accommodationPrice' => $accommodationPrice,
                'transferPrice' => $transferPrice,
                'startDate' => $request->start_date,
                'endDate' => $request->end_date,
                'course' => $course,
                'numberofWeeks' => $numberofWeeks,
                'registerationFees' => $registerationFees,
                'materialFees' => $materialFees,
            ];

            // Add Accommodation
            if (isset($accommodation)) {
                $viewParams['accommodation'] = $accommodation;
            }

            // Add Transfer
            if (isset($transfer)) {
                $viewParams['transfer'] = $transfer;
            }
            // Add Params to Session
            Session::put('quotation', $viewParams);

            // Get Total Price
            $html = view('front.quotation.show-price')->with($viewParams)->render();

            return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => ['html' => $html],
            ]);
        }
    }

    /**
     * Get course Dates
     */
    protected function getCourseDates($payload)
    {
        $courses = Course::whereIn('slug', $payload['courses'])->get();
        $quotation = Quotation::find($payload['quotation']);

        $html = '';
        foreach ($courses as $course) {
            $date_type = $course->properties['dates_type'];
            $html .= view('front.quotation._partials.dates.'.$date_type, compact('course', 'quotation'))->render();
        }

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    protected function getQuotationActivitis($payload)
    {
        $dates = $payload['dates'];
        $quotation = Quotation::find($payload['quotation']);
        $courseSlug = $payload['course'];
        $html = view('front.quotation._partials.miscellaneous.miscellaneous', compact('period', 'quotation', 'courseSlug'))->render();

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    /**
     * Get the Courses for Campus from the quotation campuses
     */
    protected function getCampusCourses($payload)
    {
        $quotation = Quotation::where('slug', $payload['quotation'])->first();
        $campuses = $payload['campuses'];

        // Check if Quotation contain only one program
        if (count($quotation->properties['courses']) > 1) {
            $html = view('front.quotation._partials.course.course', compact('campuses', 'quotation'))->render();
        } else {
            $campuses = Campus::whereIn('id', $campuses)->get();
            $course = Course::where('id', $quotation->properties['courses'][0])->first();
            $selectedCourse = $course;
            $date_type = $course->properties['dates_type'];
            $html = '';
            foreach ($campuses as $campuse) {
                $html .= view('front.quotation._partials.dates.'.$date_type, compact('course', 'quotation', 'campuse', 'selectedCourse'))->render();
            }
        }

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    /**
     * Get course Price
     */
    protected function getCoursePrice($payload, $course, $settings)
    {

        // Get Number of Weeks
        $numberOfWeeks = self::getNumberofWeeks($payload['numberOfDays']);

        // @TODO Calculate number of weeks in HighSeason
        $weeksInHighSeason = QuotationHelpers::weeksInHighSeason($settings['quotation']['hiseason_start_dates'], $settings['quotation']['hiseason_end_dates'], $payload['startDate'], $payload['endDate']);

        // Get High Season Price
        $highSeasonPrice = $weeksInHighSeason * $course->properties['hiseason_price_per_week'];

        // Get Low Season Price
        $weeksInLowSeason = $numberOfWeeks - $weeksInHighSeason;
        $lowSeasonPrice = $weeksInLowSeason * $course->properties['regular_price_per_week'];

        // Return Total Price
        return $totalPrice = $lowSeasonPrice + $highSeasonPrice;
    }

    /**
     * Get Number of Weeks Based on Number of Days
     */
    protected function getNumberofWeeks($numberOfDays)
    {

        // School Number of Week's Days ( Default Monday to Friday = 5  )
        $numberOfWeeksDays = QuotationHelpers::numberOfWeekDays();

        if (($rest = $numberOfDays - $numberOfWeeksDays) == 0) {
            $numberOfWeeks = 1;
        } else {
            $numberOfWeeks = ($rest / 7) + 1;
        }

        return $numberOfWeeks;
    }

    /**
     * Get number of Days by date value
     */
    protected function getNumberOfDays($date)
    {
        $date = explode(':', $date);
        $earlier = new \DateTime($date[0]);
        $later = new \DateTime($date[1]);

        $days = ($later->diff($earlier)->format('%a')) + 1;
        //return $days;
        return 5;
    }

    public function getSendEmailForm($payload)
    {
        $quotation = Quotation::find($payload['quotation']);
        $booking = $payload['invoice'];
        $html = view('front.quotation._partials.send-via-email', compact('quotation', 'booking'))->render();

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    public function getBookNowForm($payload)
    {
        $quotation = Quotation::find($payload['quotation']);
        $booking = $payload['invoice'];

        $html = view('front.quotation._partials.forms.register-form', compact('quotation', 'booking'))->render();

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    public function loadLoginForm($payload)
    {
        $quotation = $payload['quotation'];
        $booking = $payload['booking'];

        $html = view('front.quotation._partials.forms.login-form', compact('quotation', 'booking'))->render();

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    /**
     * Book a quotation
     *
     * @param Request $request
     * @return void
     */
    public function quotationBooking(Request $request)
    {
        $school = School::bySlug($request->school)->first();

        //@ToDo Validate the Request
        $request->validate([
            'first_name'    => 'required',
            'last_name'     => 'required',
            'email'         => 'required|email',
        ]);

        if (! $password = $request->password) {
            $password = Str::random(7);
        }

        $data = [
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'email'                 => $request->email,
            'role'                  => $request->role,
            'consent'               => $request->consent,
            'password'              => $password,
            'password_confirmation' => $password,
        ];

        // Check if user is already exist so don't log him in automatically
        if ($user = Student::where('email', $request->email)->first()) {
            // Clean Booking Table
            $this->UpdateBooking($request, $user);

            return redirect(route('school.parents', $school));
        }

        // use user RegisterController to create user
        $user = app(\App\Http\Controllers\Tenant\Auth\RegisterController::class)->create($data);

        // Clean Booking Table
        $this->UpdateBooking($request, $user);

        // Process Parent Account
        if ($user->role == 'parent') {
            // Dispatch Event New Account Created
            event(new ParentRegistred($user, null, $this->getContactType($request->role), $password));

            // Login User
            if (Auth::guard('student')->loginUsingId($user->id)) {
                return redirect(route('school.parents', $school));
            }
        }

        //Process Student account
        if ($user->role == 'student') {
            // Dispatch Event New Account Created
            event(new StudentRegistred($user, $school, $this->getContactType($request->role)));

            // Login User
            if (Auth::guard('student')->loginUsingId($user->id)) {
                return redirect(route('school.home', $school));
            }
        }
    }

    /**
     * Login To user Account
     *
     * @param Request $request
     * @param School $school
     * @return void
     */
    public function quotationLogin(Request $request, School $school)
    {

        // If the user is already logged in
        if ($user = Auth::guard('student')->user()) {
            $this->UpdateBooking($request, $user);

            return redirect(route('school.parents', $school));
        }

        // Login User
        $credentials = $request->only('email', 'password');
        if (Auth::guard('student')->attempt($credentials)) {
            $user = Auth::guard('student')->user();

            // Clean Booking
            $this->UpdateBooking($request, $user);

            return redirect(route('school.parents', $school));
        } else {
            return redirect()->back()->withInput($request->only('email'));
        }
    }

    /**
     * Get Mautic contact Type
     *
     * @param string $role
     * @return void
     */
    protected function getContactType($role = 'student')
    {
        if ($role == 'student') {
            return 'Student';
        }
        if ($role == 'parent') {
            return 'Parent';
        }

        return 'Lead';
    }

    /**
     * Update Booking Details
     *
     * @param Request $request
     * @param Student $user
     * @return void
     */
    protected function UpdateBooking(Request $request, Student $user)
    {

        // Clean the Booking mess and update the user id
        $booking = Booking::find($request->booking);
        $defaultUserId = $booking->user_id;

        $booking->update([
            'user_id' => "$user->id",
        ]);
        $booking->save();

        // Delete old occurrences
        $bookings = Booking::where('user_id', $defaultUserId)->delete();
    }

    /**
     * Send Quotation Email
     *
     * @param Request $request
     * @return void
     */
    public function sendQuotaionEmail(Request $request)
    {
        $quotation = Quotation::find($request->quotaionId);
        $booking = Booking::find($request->booking);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $request->email,
        ];
        // Dispatch Quotation Request event
        event(new QuotationEmailRequested($data, $quotation, $booking));

        return view('front.quotation.thank-you', compact('quotation', 'booking'));
    }
}
