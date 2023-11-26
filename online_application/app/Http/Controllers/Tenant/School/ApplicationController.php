<?php

namespace App\Http\Controllers\Tenant\School;

use PDF;
use Response;
use App\School;
use Carbon\Carbon;
use Illuminate\Support\Str;
use App\Tenant\Models\Field;
use Illuminate\Http\Request;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\Program;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use App\Tenant\Models\Schedule;
use App\Tenant\Models\Assistant;
use App\Tenant\Models\Submission;
use App\Tenant\Models\Application;
use App\Tenant\Models\CustomField;
use App\Tenant\Traits\Integratable;
use App\Helpers\School\SchoolHelper;
use App\Http\Controllers\Controller;
use App\Tenant\Models\InvoiceStatus;
use Illuminate\Support\Facades\Auth;
use App\Helpers\School\StudentHelpers;
use App\Tenant\Models\SubmissionStatus;
use Illuminate\Support\Facades\Session;
use App\Helpers\Application\ProgramHelpers;
use App\Helpers\Quotation\QuotationHelpers;
use App\Helpers\Permission\PermissionHelpers;
use App\Helpers\Application\SubmissionHelpers;
use App\Helpers\Application\ApplicationHelpers;
use App\Http\Controllers\Tenant\StudentController;
use App\Events\Tenant\Application\ApplicationLastStep;
use App\Events\Tenant\Application\ApplicationSubmitted;
use App\Events\Tenant\Application\ApplicationSubmissionUpdated;

class ApplicationController extends Controller
{
    use Integratable;

    protected $newSubmission = true;

    protected $invoice = null;

    /**
     * Show All School's Applications
     * @return [View]
     */
    public function index()
    {
        session()->put('submission_uuid', null);
        $student = Auth::guard('student')->user();
        $submissions = $student->submissions()->pluck('application_id');
        $student_submissions = $submissions;
        $userApplication = Application::whereIN('id', $submissions)->orWhere('published', true)->student()->with([
            'submissions' => function ($query) use ($student) {
                $query->where('student_id', $student->id)->orderBy('id', 'DESC');
            },
            'submissions.invoice',
            'submissions.invoice.status',
        ]);
        if($studentCampuses = $student->campuses()->pluck('campuses.id')->toArray())
        {
            $userApplication  = $userApplication->whereHas('campuses', function ($query) use ($studentCampuses) {
                    $query->whereIn('campuses.id', $studentCampuses)->orWhere('campuses.id' , null);
            });
        }
        $userApplication = $userApplication->get();
        $assistant = Assistant::latest()->first();
        $bookings = $student->bookings()->with('quotation', 'quotation.application')->get();

        return view('front.applications.index', compact('userApplication', 'bookings', 'assistant', 'student_submissions'));
    }

    /**
     * Show Individual Application
     * @param  School      $school      [description]
     * @param  Application $application [description]
     * @return [View]
     */
    public function show(Request $request, School $school, Application $application)
    {
        if (!$application->published) {
            return redirect(route('application.index', ['school' => $school]));
        }

        $sections = $application->sections()->with([
           'fields',
           'PaymentGateways',
        ])->get();

        // If Trying to Edit an existing application
        if (isset($request->edit)) {
            $submission = $application->submissions()->byStudent(auth()->guard('student')->user())->where('uuid', $request->edit)->first();
            $submissionUid = $request->edit;

        // Submitting new application
        } else {
            // If Multiple submissions is enabled
            if (isset($application->properties['multiple_submissions'])) {
                if (!$submissionUid = session()->get('submission_uuid')) {
                    $submission = null;
                    $submissionUid = Str::random(16);
                    session()->put('submission_uuid', $submissionUid);
                } else {
                    $submission = $application->submissions()->byStudent(auth()->guard('student')->user())->where('uuid', $submissionUid)->first();
                }
            } else {
                // check if First Page is Save
                if ($submission = $application->submissions()->byStudent(auth()->guard('student')->user())->first()) {
                    $submissionUid = $submission->uuid;
                    $status = optional($submission)->status;
                } else {
                    $submission = null;
                    $submissionUid = Str::random(16);
                }
            }
        }


        if (isset($status) && $status == 'Submitted' && $application->onTimeSubmission) {
            return redirect(route('application.index', ['school' => $school]));
        }

        // if student doesn't have submmitted date
        if (!$submission) {
            $submission = new Submission();
            $submission->data = $this->getParentData($application);
        }

        $studentInvoice = null;
        // check if application has payment in sections
        $sections->pluck('fields')->each(function ($field) use ($application, &$studentInvoice, &$submission) {
            if ($field->contains('field_type', 'payment')) {
                $studentInvoice = Auth::guard('student')
                    ->user()
                    ->invoices()
                    ->where('application_id', $application->id)
                    ->first();

                if (! $studentInvoice) {
                    // Create Invoice
                    $this->createApplicationInvoice($application, $submission);
                }
            }
        });

        if (! $studentInvoice) {
            $invoice = $this->invoice;
            if ($invoice) {
                $invoice->load('status');
            }
        } else {
            $invoice = $studentInvoice->load('status');
        }

        $integrable = $this->inetgration();

        if ($request->has('assistant')) {
            $assistant = Assistant::find($request->assistant);
        } else {
            $assistant = null;
        }
        return view(
            'front.applications.show',
            compact('application', 'sections', 'school', 'submission', 'invoice', 'integrable', 'submissionUid', 'assistant')
        );
    }

    /**
     * Show Individual Application
     * @param  School      $school      [description]
     * @param  Application $application [description]
     * @return [View]
     */
    public function preview(Request $request, School $school, Application $application)
    {
        /* if (!$application->published) {
            return redirect(route('application.index', ['school' => $school]));
        } */
        if ($application->object == "agency") {
            return redirect()->back();
        }
        $sections = $application->sections()->with([
           'fields',
           'PaymentGateways',
        ])->get();
        $integrable = $this->inetgration();
        if ($request->has('assistant')) {
            $assistant = Assistant::find($request->assistant);
        } else {
            $assistant = null;
        }
        $preview = true;
        return view(
            'front.applications.preview',
            compact('application', 'sections', 'school', 'integrable', 'assistant', 'preview')
        );
    }

    /**
     * Create Application Invoiceif not created
     *
     * @param Application $application
     * @return void
     */
    protected function createApplicationInvoice(Application $application, Submission $submission)
    {
        $totalPrice = $application->properties['application_fees'];
        $invoice = new Invoice();
        $invoice->uid = rand(100000, 100000000);
        $invoice->total = $totalPrice;
        $invoice->payment_gateway = $application->paymentGateway()->slug;
        $invoice->application_id = $application->id;
        $invoice->submission_id = $submission->id;
        $invoice->student_id = Auth::guard('student')->user()->id;
        $invoice->enabled = true;
        $invoice->save();
        $status = new InvoiceStatus();
        $status->status = 'Invoice Created';
        $invoice->status()->save($status);
        $this->invoice = $invoice;
    }

    protected function getParentData(Application $application)
    {
        // Find Brother ID
        $user = auth()->guard('student')->user();

        if ($parent = Student::find($user->parent_id)) {
            $brothersIds = Student::where(
                ['parent_id' => $parent->id]
            )->where('id', '<>', $user->id)->pluck('id')->toArray();

            // Get Submission Data by brothers
            $submission = $application->submissions()->whereIn(
                'student_id',
                $brothersIds
            )->orderBy('id', 'DESC')->first();

            $sections = $application->sections()->with([
                'fields' => function ($query) {
                    $query->where('object', 'parent');
                },
            ])->get();

            $data = [];

            foreach ($sections as $section) {
                foreach ($section->fields as $field) {
                    if (isset($submission->data[$field->name])) {
                        $data[$field->name] = $submission->data[$field->name];
                    }
                }
            }

            return $data;
        }

        return null;
    }

    public function submit(Request $request, School $school, Application $application)
    {
        $student = auth()->guard('student')->user();
        $setting = Setting::byGroup();

        if ($request->submissionUid) {
            $submission = Submission::where('uuid', $request->submissionUid)->first();
        }

        if (isset($submission) && $submission && $this->updateApplication($request, $submission)) {
            event(new ApplicationSubmissionUpdated($submission, $student, $application, $setting, $school));
        }

        elseif ($submission = $this->submitApplication($request, $application, $student)) {
            event(new ApplicationSubmitted($submission, $student, $application, $setting, $school));
        }

        $payment = $application->PaymentGateways()->first();

        $invoice = $student->invoices()
            ->where('enabled', true)
            ->where('submission_id', $submission->id)
            ->orderByDesc('created_at')
            ->first();

        if (! $invoice) {
            $invoice = $student->invoices()
                ->where('enabled', true)
                ->where('application_id', $application->id)
                ->orderByDesc('created_at')
                ->first();
        }

        if ($request->status !== 'Submitted') {
            return response()->json('true');
        }

        $submission = $this->lockSubmission($submission, $application);
        event(new ApplicationLastStep($submission, $student, $application, $setting, $school));

        if (auth()->guard('agent')->check()) {
            return redirect()->route('school.agent.home', [
                'school' => $school
            ]);
        }

        if (session()->has('impersonated-by') && session('impersonated-by') == 'user') {
            auth()->guard('student')->logout();

            return app(StudentController::class)->stopImportsonating($request);
        }

        if (isset($payment) && isset($invoice) && $invoice->isNotPaid) {
            return redirect()->route('show.payment', [
                'school' => $school,
                'invoice' => $invoice,
                'application' => $application,
            ]);
        }

        elseif (session('child-impersonate') && isset($application->quotation->properties['enable_thank_you_page'])) {
            return redirect(route('application.thank.you', [
                'school' => $school,
                'application' => $application,
                'booking' => $submission->booking_id,
            ]));
        }

        elseif ($action = $application->actions()->where('action', 'redirect-to-url')->first()) {
            return redirect($action->properties['url']);
        }

        return redirect(route('application.index', $school));
    }

    public function review(Request $request, School $school, Application $application)
    {
        $student = auth()->guard('student')->user();
        $excludes = ['helper', 'hidden'];
        if ($submission = Submission::byStudentAndApplication($student, $application)->first()) {
            $html = view(
                'front.applications.application-layouts.rounded.review.content',
                compact('submission', 'application', 'excludes')
            )->render();

            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => [
                    'html' => $html,
                    ],
                    ]);
        }
    }

    /**
     * Inline Field Edit
     */
    public function editField(Request $request, School $school, Submission $submission, Field $field)
    {
        $student = auth()->guard('student')->user();
        $agent = auth()->guard('agent')->user();
        $user = auth()->user();
        $filedName = $request->name;

        if (! $student && ! $user && ! $agent) {
            abort(401);
        }

        if ($student && $student->id != $submission->student->id) {
            abort(419);
        }

        if(in_array($field->properties['type'],['program' , 'course'] ))
        {
            $data = $submission->data;
            $data[$filedName][$request->object] = $request->value;

            $submission->data = array_merge($submission->data, $data);

        }else{

            $submission->data = array_merge($submission->data, [$filedName => $request->value]);
        }



        $submission->save();
    }

    public function getFieldData(Request $request, School $school, Application $application, Field $field)
    {
        $data = [];


        if($request->has('object')){

            $object = explode("|" , $request->object);

            switch ($object[1]) {
                case 'campus':
                $campuses = Campus::all();
                foreach ($campuses as $campus) {
                    $data[] = [
                        'value' => $campus->slug,
                        'text' => $campus->title,
                    ];
                }
                return $data;
                break;

                case 'programs':
                $programs = Program::all();
                foreach ($programs as $program) {
                    $data[] = [
                        'value' => $program->slug,
                        'text' => $program->title,
                    ];
                }
                return $data;
                break;


            }
        }


        if ($field->data) {
            foreach ($field->data as $value => $text) {
                $data[] = [
                    'value' => $value,
                    'text' => $text,
                ];
                $data[] = [
                    'value' => $value,
                    'text' => $text,
                ];
            }
        }

        return $data;
    }

    protected function isValidField($field, $value = null)
    {
        return false;
    }

    /**
     * Update Application
     *
     * @param Submission $submission
     * @param Request $request
     * @return bool
     */
    protected function updateApplication(Request $request, Submission $submission)
    {
        $application = $submission->application;
        $submission->data = $this->getSubmittedData($request->toArray(), $submission->data, false, $application);
        if ($status = $request->status) {
            $submission->status = $status;
        }
        if ($step = $request->step) {
            $submission->properties = ['step' => $step];
            $submission->steps_progress_status = ApplicationHelpers::stepPercentageProgress($step, $application);
        }
        if ($submission->save()) {
            return true;
        }
        return false;
    }

    /**
     * Create New Submission
     *
     * @param Request $request
     * @return Submission
     */
    protected function submitApplication(Request $request, Application $application, Student $student)
    {

        // Create New Submission
        $submission = new Submission();
        // Save Data
        $submission->data = $this->getSubmittedData($request->toArray(), [], true, $application);

        $submission->uuid = $request->submissionUid;

        if ($status = $request->status) {
            $submission->status = $status;
        } else {
            $submission->status = 'Started';
        }

        if ($step = $request->step) {
            $submission->properties = ['step' => $step];
            $submission->steps_progress_status = ApplicationHelpers::stepPercentageProgress($step, $application);
        }
        //Assign Student
        $submission->student()->associate($student);

        //Assign Application
        $submission->application()->associate($application);

        // Assign Booking if Available
        if ($request->has('booking_id')) {
            $submission->booking_id = $request->booking_id;
        }

        // Save Application Submission
        $submission->save();

        return $submission;
    }

    /**
     * Show Thank you Page
     *
     * @param School $school
     * @param Application $application
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function thankYou(School $school, Application $application)
    {
        $booking = [];

        if (request('booking') != null) {
            $booking = Booking::find(request('booking'));
        }
        $user = Auth::guard('student')->user();

        $invoice = Invoice::where([
            'booking_id' => $booking->id,
            'student_id' => $user->id,
        ])->first();

        if ($user->parent_id) {
            $user = Student::find($user->parent_id)->first();
        }

        return view('front.applications.thank_you.index', compact(
            'application',
            'school',
            'booking',
            'user',
            'invoice'
        ));
    }

    /**
     * Redirect to URL
     *
     * @param [type] $url
     * @return void
     */
    protected function redirectToExternalPage($url)
    {
        return "<script>window.open('".$url."', '_blank')</script>";
    }

    /**
     * Get Form Data
     * @param  [array] $data          [submitted From Data]
     * @param  array  $submittedData [Saved Application Data]
     * @return [array]                [data to save]
     */
    protected function getSubmittedData(array $data, $submittedData, $isNewSubmission, $application)
    {
        // Unset unused
        unset($data['_token']);
        unset($data['section']);

        $fields = $this->getFields($application->sections()->get());

        // if new application submission return filled fields only
        if ($isNewSubmission) {
            return array_filter($data);
        }
        $newFomData = [];

        // Overwrite submitted data if different
        foreach ($submittedData as $key => $value) {
            if (array_key_exists($key, $fields)) {
                SubmissionHelpers::saveStudentDataFromApplications($fields[$key], $value, auth()->guard('student')->user());
            }

            // update Submitted Data if different than new Form Data
            if (isset($data[$key])) {
                // if is array override the data
                if (is_array($value)) {
                    $submittedData[$key] = $data[$key];
                } else {
                    if ($data[$key] != $value) {
                        $submittedData[$key] = $data[$key];
                    }
                }
            }
            // Remove Data from Submitted Data if not exist in the new form data
            if (! in_array($key, array_keys($data))) {
                unset($submittedData[$key]);
            }
        }

        return array_filter($submittedData + $data);
    }

    protected function getFieldsNames($sections)
    {
        $names = [];
        foreach ($sections as $section) {
            foreach ($section->fields as $field) {
                array_push($names, $field->name);
            }
        }

        return $names;
    }

    protected function getFields($sections)
    {
        $fields = [];
        foreach ($sections as $section) {
            foreach ($section->fields as $field) {
                $fields[$field->name] = $field;
            }
        }

        return $fields;
    }

    /**
     * Create Dynamic Valdation Rules
     * @param  [Request] $request
     * @param  [Application] $application
     * @return [Bool]
     */
    public function validateApplication(Request $request, Application $application)
    {
        // Validation Rules
        $rules = [];

        $sections = $application->sections()->with('fields')->get();

        foreach ($sections as $section) {
            foreach ($section->fields as $field) {
                $rules[$field->name] = $this->getValidationRules($field->properties);
            }
        }

        return $this->validate($request, $rules);
    }

    public function showInstructions(School $school, Application $application)
    {
        return view('front.applications._partials.application.instructions', compact('application'))->render();
    }

    /**
     * Create Validation Rules for each field type
     * @param  [array] $properties
     * @return [array] $rules
     */
    protected function getValidationRules($properties)
    {
        $rules = [];

        // Required Fields
        if ($properties['validation']['required']) {
            array_push($rules, 'required');
        }

        //Text

        if ($properties['type'] == 'text') {
            array_push($rules, 'string');

            array_push($rules, 'max:255');
        }

        //Email

        if ($properties['type'] == 'email') {
            array_push($rules, 'string');

            array_push($rules, 'max:255');
        }

        //TextArea

        if ($properties['type'] == 'text-area') {
        }

        return implode('|', $rules);
    }

    /**
     * Get synced Data
     *
     * @param School $school
     * @param Application $application
     * @param Field $field
     * @param Request $request
     * @return void
     */
    public function getSyncData(Request $request, School $school, Application $application, Field $field)
    {
        $formData = null;
        if($request->filled('form')){
            parse_str($request->form , $formData);
            $formData = array_filter($formData);
        }
        $data = [null => __('--Select--')];
        $target = $request->target;
        $source = $request->source;
        $schedule = Schedule::get()->keyBy('id')->toArray();
        $settings = Setting::byGroup();
        // Get Program
        if ($target == 'Program') {
            // By Campus
            if ($source == 'Campus') {
                $programs = Campus::bySlug($request->value)->first()->programs;
                if ($programs->count()) {
                    foreach ($programs as $program) {
                        $data[$program->slug] = $program->title;
                    }
                }
            }
        }

        if ($target == 'Campus') {
            if ($source == 'Program') {
                $campuses = optional(Program::bySlug($request->value)->first())->campuses;
                if ($campuses && $campuses->count()) {
                    foreach ($campuses as $campus) {
                        $data[$campus->slug] = $campus->title;
                    }
                }
            }
            if ($source == 'Courses') {
                $campuses = optional(Course::bySlug($request->value)->first())->campuses;
                if ($campuses && $campuses->count()) {
                    foreach ($campuses as $campus) {
                        $data[$campus->slug] = $campus->title;
                    }
                }
            }
        }

        if ($target == 'Program') {
            //if no campus should be a custom field
            if ($source != 'Campus') {
                $program = Program::where('slug', $request->value)->first();
                if ($program) {
                    if (isset($program->properties['customfields'][$source])) {
                        $customfields = $program->properties['customfields'][$source];
                        foreach ($customfields as $values) {
                            $data = array_merge($data, $values);
                        }
                    }
                }
            }
        }

        if ($target == 'Courses') {
            if ($source != 'Campus' && $source != 'Program') {
                $course = Course::where('slug', $request->value)->first();
                if (isset($program->properties['customfields'][$source])) {
                    $customfields = $course->properties['customfields'][$source];
                    foreach ($customfields as $values) {
                        $data = array_merge($data, $values);
                    }
                }
            }
        }

        // Get start Dates
        if (in_array($target, ['Dates', 'Start_Dates'])) {
            if($student = Auth::guard('student')->user()){
                $campus = $student->campus;
            }else{
                $campus = null;
            }

            //By Program
            if ($source == 'Program') {
                $program = Program::bySlug($request->value)->first();

                // Get Intake Type
                if ($program && $program->properties['dates_type'] == 'specific-intakes') {
                    $dates = $program->properties['intake_date'];
                    foreach ($dates as $key => $date) {
                        // Formate Date
                        $date = date('F Y', strtotime($date));
                        $data[$date] = $date;
                    }
                }
                if ($program && $program->properties['dates_type'] == 'specific-dates') {

                    $dates = $program->properties['start_date'];

                    foreach ($dates as $key => $date) {
                        // Formate Date
                        $formatedDate = date('F Y', strtotime($date));
                        $data[$date] = $formatedDate;
                    }
                }
            } elseif ($source == 'Courses') {
                $course = Course::bySlug($request->value)->first();

                if ($course) {
                    $dates = $course->dates()->get();

                    foreach ($dates as $d) {

                        if ($course->properties['dates_type'] == 'single-day') {
                            if ($d->properties['date'] <= now()) {
                                continue;
                            }
                            $val = QuotationHelpers::formateDateStarTimeEndTime(
                                $d->properties['date']
                                .' '.$d->properties['start_time']
                                .' '.$d->properties['end_time']
                            );
                        } elseif ($course->properties['dates_type'] == 'specific-dates') {

                            if ($d->properties['start_date'] <= now()) {
                                continue;
                            }

                            $dateSchedule = $schedule[$d->properties['date_schudel']];

                            $val = QuotationHelpers::formateCourseStartEndDates(
                                $d->properties['start_date'].' '.$d->properties['end_date'],
                                ' ',
                                '('.date('H:i', strtotime($dateSchedule['start_time'])).'-'.date('H:i', strtotime($dateSchedule['end_time'])).') '. (isset($d->properties['date_price']) && !empty($d->properties['date_price']) ? $d->properties['date_price'] . '' . $settings['school']['default_currency'] : '')
                            );
                        }

                        $data[$val] = $val;
                    }
                }
            }
        }

        return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['data' => $data],
        ]);
    }

    /**
     * Get the value of application
     */
    public function getApplication()
    {
        return $this->application;
    }

    /**
     * Set the value of application
     *
     * @return  self
     */
    public function setApplication($application)
    {
        $this->application = $application;

        return $this;
    }

    public function redirectAfterSubmit($school, $application_slug, $submission)
    {
        $student = auth()->guard('student')->user();
        $application = Application::where('slug', $application_slug)->first();

        // check if the user has Invoice and the Invoice is enabled
        if (!$invoice = $student->invoices()->where('enabled', true)->where(
            'submission_id',
            $submission->id
        )->orderBy('created_at', 'DESC')->first()) {
            $invoice = $student->invoices()->where('enabled', true)->where(
                'application_id',
                $application->id
            )->orderBy('created_at', 'DESC')->first();
        }
        if (isset($payment) && isset($invoice) && $invoice->status->last()->status != 'Paid') {
            // check if Application has a registeration fees and the invoice is created and not paid
            return redirect(route('show.payment', [
                'school' => $school, 'invoice' => $invoice, 'application' => $application, ]));
            exit();

        // check if the application is submitted by parent
        } elseif (session('child-impersonate')) {
            if (isset($application->quotation->properties['enable_thank_you_page'])) {
                return redirect(route('application.thank.you', [
                    'school ' => $school, 'application' => $application, 'booking' => $submission->booking_id, ]));
            }

            //return redirect(route('school.parents', $school));
            exit();

        // Check if application has redirect actions
        } elseif ($action = $application->actions()->where('action', 'redirect-to-url')->first()) {
            return redirect($action->properties['url']);
            exit();
        } else {
            //default Redirection
            return redirect(route('application.index', $school));
            exit();
        }
    }

    private function lockSubmission(Submission $submission, Application $application)
    {
        if (! $application->lockable()) {
            return $submission;
        }

        $properties = $submission->properties;
        $properties['lock'] = 1;
        SubmissionHelpers::newSubmissionStatus($submission, 'Locked');
        $submission->properties = $properties;
        $submission->save();

        return $submission;
    }

    public function deleteApplicationSubmission($school, $application, $submission)
    {
        $submission = Submission::findOrFail($submission);
        if ($response = $submission->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => ['removedId' => $submission->id],
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

    public function showSubmittedApplications()
    {
        $student = Auth::guard('student')->user();

        $submissions = $student->submissions()->pluck('application_id');
        $userApplication = Application::whereIN('id', $submissions)->orWhere('published', true)->student()->with([
            'invoices' => function ($query) use ($student) {
                $query->where('student_id', $student->id);
            },
            'invoices.status',
            'submissions' => function ($query) use ($student) {
                $query->where('student_id', $student->id)->orderBy('id', 'DESC');
            },
        ])->get();
        $assistant = Assistant::latest()->first();
        $bookings = $student->bookings()->with('quotation', 'quotation.application')->get();

        return view('front.applications.submittedapplications', compact('userApplication', 'bookings', 'assistant'));
    }

    public function newApplications()
    {

        StudentHelpers::hasApplicationsToSubmit();

        session()->put('submission_uuid', null);
        $student = Auth::guard('student')->user();
        $studentApplications = StudentHelpers::getStudentApplications();
        $assistant = Assistant::latest()->first();
        $bookings = $student->bookings()->with('quotation', 'quotation.application')->get();

        return view('front.applications.newapplications', compact('studentApplications', 'bookings', 'assistant'));
    }

    public function showInvoice(School $school, Invoice $invoice)
    {
        $invoice->load('booking', 'student', 'status', 'application');
        $settings = Setting::byGroup();
        $currency = isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : '$';

        //if ($action == 'view') {
        $html = view('back.students.invoices.invoice', compact('invoice', 'settings', 'currency'))->render();

        return PDF::loadHTML($html)->stream();
        ///}
    }

    public function filterProgramsList($request)
    {
        $campus = $request->get('campus' , null);
        $html = view( 'front.auth._partials.programs' , ['campus' => $campus] )->render();
        return Response::json(
        [
            'status'   => 200,
            'response' => 'success',
            'extra'    => [
                'html' => $html
            ],
        ]
        );


    }
}
