<?php

namespace App\Http\Controllers\Tenant\School;

use App\Events\Tenant\Application\ApplicationLastStep;
use App\Events\Tenant\Application\ApplicationSubmissionUpdated;
use App\Events\Tenant\Application\ApplicationSubmitted;
use App\Helpers\Application\PaymentHelpers;
use App\Helpers\Invoice\InvoiceHelpers;
use App\Http\Controllers\Controller;
use App\School;
use App\Services\Payment\StripeService;
use App\Tenant\Models\Application;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Plugin;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Illuminate\Validation\ValidationException;

class NoAuthApplicationController extends Controller
{
    public function showNoLogin(Request $request)
    {
        $get_variables = request()->query();
        $school = School::bySlug($request->school)->first();
        session()->put('tenant', $school->uuid);

        $application = Application::bySlug($request->application)->firstOrFail();
        $sections = $application->sections()->with(['fields', 'PaymentGateways'])->get();

        return view(
            'front.applications.show',
            compact('application', 'sections', 'school', 'get_variables')
        );
    }

    /**
     * Submit New Application
     * @param Request $request
     * @param School $school
     * @param Application $application
     * @return [type]                   [description]
     */
    public function submit(Request $request, School $school, $application)
    {
        $application = Application::where('slug', $request->application_slug)->first();

        $setting = Setting::byGroup();

        $sections = $application->sections()->get();

        // Validate Form Data
        //$this->validateApplication($request , $application);

        // Get Application Fields Names
        $fieldData = $this->getFieldsNames($sections);

        if (isset($request->plugin_payment) && $request->plugin_payment != '') {
            $invoice_form = $this->managePayment($request, $application); //Passing by Reference
        }
        $studentData = [];
        foreach ($fieldData as $slug=>$field) {
            if(isset($field->properties['map'])){
                $map = explode("|" , $field->properties['map']);
                $studentData[$map[1]] = $request->input($slug , null);
            }
        }
        // Create Student and Log him in
        if(!$student = $this->createStudent($studentData ,$request, $sections)){
            return null;
        }
        if ($submission = Submission::byStudentAndApplication($student, $application)->first()) {
            // Update  Application
            if ($this->updateApplication($submission, $request)) {
                // Update Submission Events
                event(new ApplicationSubmissionUpdated($submission, $student, $application, $setting, $school));
            }
            // Run Last Step Actions
            if ($request->status == 'Submitted') {
                event(new ApplicationLastStep($submission, $student, $application, $setting, $school));
            } else {
                // stop if not last step
                return response()->json('true');
            }
        } else {
            // Submit Application
            if ($submission = $this->submitApplication($request, $application, $student)) {
                // New Submission Events
                event(new ApplicationSubmitted($submission, $student, $application, $setting, $school));
            }
        }

        if (isset($invoice_form)) {
            $invoice_form->student_id = $student->id;
            $invoice_form->save();

            $this->payInvoice($invoice_form, $student->id);

            $product = ('App\Tenant\Models\\'.$request->category)::findOrFail($request->product);
            $product_payload = [
                'amount'        => $request->amount,
                'quantity'      => 1,
                'description'   => $product->title,
            ];
            InvoiceHelpers::addInvoiceable($invoice_form, $product, $student->id, $product_payload);
        }

        // Check if application Has PaymentGateway and the user has invoice
        // Application Payment GateWay
        $payment = $application->PaymentGateways->first();

        if (isset($request->invoice) && $request->invoice != '') {
            $invoice = $request->invoice;
        } else {
            // check if the user has Invoice and the Invoice is enabled
            $invoice = $student->invoices()->where('enabled', true)->orderBy('created_at', 'DESC')->first();
            //dd($invoice);
        }

        if (isset($request->redirect) && $request->redirect != '') {
            return redirect(
                route(
                    'submitted.successful',
                    [
                    'school' => $request->school,
                    'redirect' => $request->redirect
                    ]
                )
            );
        } elseif ($action = $application->actions()->where('action', 'eversign-signature')->first()) {
            return redirect()->route('sign.after.eversign', [$school, $application, $submission, $action, $invoice]);
        } elseif (isset($payment) && isset($invoice) && $invoice->status->last()->status != 'Paid') {
            return redirect(
                route(
                    'show.payment.no.login',
                    ['school' => $school, 'invoice' => $invoice, 'application' => $application]
                )
            );
            exit();
        // check if the application is submitted by parent
        } elseif (session('child-impersonate')) {
            if (isset($application->quotation->properties['enable_thank_you_page'])) {
                return redirect(
                    route(
                        'application.thank.you',
                        ['school ' => $school, 'application' => $application, 'booking' => $submission->booking_id]
                    )
                );
            }

            //return redirect(route('school.parents', $school));
            exit();
        // Check if application has redirect actions
        } elseif ($action = $application->actions()->where('action', 'redirect-to-url')->first()) {
            return redirect($action->properties['url']);
            exit();
        } else {
            //default Redirection
            return redirect()->back()->withSuccess('Your application submitted successfuly');
            exit();
        }
    }

    /**
     * Create New Student
     *
     * @param Request $request
     * @param [type] $sections
     * @return Student
     */
    protected function createStudent($studentData ,Request $request, $sections)
    {
        if(!isset($studentData['email']) || !isset($studentData['first_name']) || !isset($studentData['last_name'])){
            return null;
        }
        $student = Student::firstOrNew(['email' => $studentData['email']]);
        $student->first_name = $studentData['first_name'];
        $student->last_name = $studentData['last_name'];
        $student->password = Str::random(6);
        $student->save();
        return $student;
    }

    /**
     * Create New Submission
     *
     * @param Submission $submission
     * @param Request $request
     * @return bool
     */
    protected function updateApplication(Submission $submission, Request $request)
    {
        $submission->data = $this->getSubmittedData($request->toArray(), $submission->data, false);

        if ($status = $request->status) {
            $submission->status = $status;
        }
        if ($step = $request->step) {
            $submission->properties = ['step' => $step];
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
        $submission->data = $this->getSubmittedData($request->toArray(), [], true);

        if ($status = $request->status) {
            $submission->status = 'Started';
        }

        if ($step = $request->step) {
            $submission->properties = ['step' => $step];
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
     * @return void
     */
    public function thankYou(School $school, Application $application)
    {
        $booking = Booking::find(request('booking'))->first();
        $user = Auth::guard('student')->user();

        if ($user->parent_id) {
            $user = Student::find($user->parent_id)->first();
        }

        return view('front.applications.thank_you.index', compact('application', 'school', 'booking', 'user'));
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
     * @param array $submittedData [Saved Application Data]
     * @return [array]                [data to save]
     */
    protected function getSubmittedData(array $data, $submittedData, $isNewSubmission)
    {
        // Unset unused
        unset($data['_token']);
        unset($data['section']);

        // if new application submission return filled fields only
        if ($isNewSubmission) {
            return array_filter($data);
        }

        $newFomData = [];

        // Overwrite submitted data if different
        foreach ($submittedData as $key => $value) {
            // update Submitted Data if different than new Form Data
            if (isset($data[$key])) {
                if ($data[$key] != $value) {
                    $submittedData[$key] = $data[$key];
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
                $names[$field->name] = $field;
            }
        }

        return $names;
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

    public function submissionSuccess(Request $request)
    {
        $redirect = $request->redirect;

        return view(
            'front.applications.application-layouts.iframe.success.success',
            compact('redirect')
        );
    }

    private function managePayment(Request &$request, $application)
    {
        $request->validate([
            'card_no'       => 'required',
            'ccExpiryMonth' => 'required',
            'ccExpiryYear'  => 'required',
            'cvvNumber'     => 'required',
        ]);

        $plugin = Plugin::findOrFail($request->plugin_payment);

        if ($plugin->name == 'stripe') {
            $stripe_service = new StripeService($plugin->properties['secret_api_key']);
            $request->request->remove('public_api_key');
            $request->request->remove('plugin_payment');

            $settings = Setting::byGroup();
            $default_currency = isset($settings['school']['default_currency']) ? $settings['school']['default_currency'] : 'CAD';

            try {
                $payment_details = [
                    'amount' => $request->amount,
                    'currency_code' => $default_currency,
                    'item_name' => $application->title,
                    'invoice_id' => 1,
                    'token' => $stripe_service->createToken(
                        $request->only(['card_no', 'ccExpiryMonth', 'ccExpiryYear', 'cvvNumber'])
                    ),
                ];
            } catch (\Exception $exception) {
                throw ValidationException::withMessages(['card_no' => $exception->getMessage()]);
            }

            $request->request->remove('card_no');
            $request->request->remove('ccExpiryMonth');
            $request->request->remove('ccExpiryYear');
            $request->request->remove('cvvNumber');

            try {
                $stripe_service->paymentProcess($payment_details);
            } catch (\Exception $exception) {
                throw ValidationException::withMessages(['card_no' => $exception->getMessage()]);
            }
        }

        return $this->createInvoice($request->amount, $plugin->name, $application);
    }

    private function createInvoice($amount, $payment_gateway)
    {
        $invoice_payload = ['total' => $amount, 'payment_gateway' => $payment_gateway];

        return InvoiceHelpers::addPolymorphInvoice($invoice_payload);
    }

    private function payInvoice(\App\Tenant\Models\Invoice $invoice, $student_id)
    {
        PaymentHelpers::addPayment($invoice, $student_id, $invoice->total);
    }
}
