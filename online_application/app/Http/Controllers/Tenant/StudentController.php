<?php

namespace App\Http\Controllers\Tenant;

use Str;
use Auth;
use Mail;
use Session;
use Storage;
use Response;
use App\School;
use App\Tenant\Models\Date;
use App\Tenant\Models\Group;
use Illuminate\Http\Request;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Invoice;
use App\Tenant\Models\Message;
use App\Tenant\Models\Program;
use App\Tenant\Models\Student;
use App\Exports\StudentsExport;
use App\Tenant\Models\Submission;
use App\Rules\School\StudentExist;
use App\Tenant\Models\Application;
use App\Tenant\Traits\HasCampuses;
use App\Tenant\Traits\Integratable;
use App\Helpers\School\SchoolHelper;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\Invoice\InvoiceHelpers;
use App\Events\Tenant\Student\StudentCreated;
use App\Helpers\Permission\PermissionHelpers;
use App\Mail\Tenant\SendInvoiceReminderEmail;
use App\Helpers\Application\SubmissionHelpers;
use App\Http\Controllers\Tenant\Auth\RegisterController;
use App\Tenant\Models\CustomField;

class StudentController extends Controller
{
    use Integratable;
    use HasCampuses;
    const  PERMISSION_BASE = "contact";

    public function __construct()
    {
        $this->middleware('plan.features:application')
            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    }

    public function index(Request $request)
    {
        // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }

        $filter['role'] = $request->has('type') ? $request->type : 'student';

        $students = $this->getStudentsList($filter , $permissions['campusesView|' . self::PERMISSION_BASE]);

        $applications = Application::get();

        $params = [
            'modelName' => 'students',
        ];

        return view('back.students.index', compact('students', 'applications', 'params' , 'permissions'));
    }

    public function getFilteredStudents($payload)
    {

        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);



        $filter = [];

        if (isset($payload['filterBy'])) {
            switch ($payload['filterBy']) {
                case 'date':
                    $filter = [
                        ['students.created_at', '>=', $payload['startDate']],
                        ['students.created_at', '<=', $payload['endDate']],
                    ];
                    break;

                case 'text':
                    $values = explode(' ', $payload['string']);
                    $filter = function ($query) use ($values) {
                        foreach ($values as $value) {
                            $query->where('students.first_name', 'like', "%$value%")
                                ->orWhere('students.last_name', 'like', "%$value%")
                                ->orWhere('students.email', 'like', "%$value%");
                        }
                    };

                    break;

                default:
                    $filter = [];
                    break;
            }
        }

        $students = $this->getStudentsList($filter , $permissions['campusesView|' . self::PERMISSION_BASE]);

        $applications = Application::get();

        $html = view('back.students._partials.students', compact('students', 'applications' , 'permissions'))->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => $html],
        ]);
    }

    public function getStudentsList($filter = [] , $viewThroughCampsues = false)
    {
        $students = Student::with(
            'submissions',
            'submissions.application',
            'agent',
            'agent.agency',
            'invoices',
            'invoices.status'
        );
        if (! empty($filter) && ! empty($students)) {
            $students->where($filter);
        }


        if(!$viewThroughCampsues){
            $students->byCampus($viewThroughCampsues);
        }

        return $students->orderBy('students.created_at', 'desc')->with('submissions')->get();
    }

    public function create()
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

         if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }
        $applications = Application::byCampus($permissions['campusesCreate|contact'])->get();
        $campuses = $this->getUserCampusesList();
        return view(
            'back.students.create',
            compact('permissions' , 'applications' , 'campuses')
        );
    }

    public function store(Request $request)
    {


        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
             return PermissionHelpers::accessDenied();

        }
        $request->validate([
            'first_name'            => 'required|string|max:255',
                'last_name'             => 'required|string|max:255',
                'role'                  => 'required|string|max:255',
                'campuses'              => 'required|max:255',
                'email'                 => ['required', 'string', 'email', new StudentExist()],
            ]
        );

        $password = Str::random(8);

        $data = $request->all();
        $data['password'] =  $password ;
        $student = app(RegisterController::class)->create($data);
        $sendNotification = (isset($request->send_email)) ? true : false;
        //School
        $school = session('school');

        if ($student instanceof \App\Tenant\Models\Student) {

            if($request->has('campuses') && is_array(array_filter($request->campuses))   ){
                $student->campuses()->sync($request->campuses);
            }

            // attache Login URL to the Data before passing to Event
            $data['login_url'] = route('school.login', $school);

            // Add The language
            //$data['language'] = $request->language;
            $data['language'] = 'en';

            //Dispatch Student Created Event
            event(new StudentCreated($student, $sendNotification, $data, 'Applicant'));

            $application_url = null;
            if ($request->application != 0) {
                // If Application is selected
                $application = Application::findorFail($request->application);
                //Impersonate the student
                if($this->impersonate($student , route('students.index'))){
                    return redirect()->route(
                        'application.show', [
                            'school' => $school,
                            'application' => $application
                        ]);
                }else{
                    return redirect(route('students.index'));
                }
            }

        }

        return redirect(route('students.index'));

    }

    public function impersonateStudent(Request $request ,Student $student)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE,
            'delete|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE,
            'view|submission',
            'edit|submission',
            'create|submission',
            'delete|submission',
            'campusesView|submission',
            'campusesEdit|submission',
            'campusesCreate|submission',
            'campusesDelete|submission',
        ]);
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', $student)) {
            return PermissionHelpers::accessDenied();
        }

        if ($this->impersonate($student , route('students.show' , $student))) {
            return redirect(route('school.home',
                [
                    'school' => session('school')
                ]
            ));
        } else {
            return redirect()->back();
        }



    }

    public function impersonate(Student $student , $redirectTo = null)
    {
        // Sign out All Student in the same session
        Auth::guard('student')->logout();

        Session::put('impersonate', $student->id);
        Session::put('impersonated-by', "user");
        if($redirectTo){
            Session::put('redirect-to', $redirectTo);
        }

        if (Session::has('impersonate')) {
            return true;
        }
        return false;
    }

    public function stopImportsonating(Request $request)
    {
        $redirectTo = session()->has('redirect-to') ? session('redirect-to') : route('students.index');
        Session::forget('impersonate');
        Session::forget('impersonated-by');
        Session::forget('redirect-to');

        return redirect($redirectTo);

    }

    public function show(Request $request, Student $student)
    {
        // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE,
            'delete|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE,
            'view|submission',
            'edit|submission',
            'create|submission',
            'delete|submission',
            'campusesView|submission',
            'campusesEdit|submission',
            'campusesCreate|submission',
            'campusesDelete|submission',
        ]);
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', $student)) {
            return PermissionHelpers::accessDenied();
        }
        $request_link = $request->link;

        $applicant = $student->load(
            ['submissions',
            'submissions.application',
            'submissions.statuses',
            'submissions.application.sections',
            'submissions.application.sections.fields',
            'contracts',
            'agent',
            'agent.agency',
            'invoices',
            'invoices.status',
            'attendances',
            'attendances.lesson']
        );

        $courses = Course::all();
        $program = Program::all();
        $campus = Campus::all();
        $date = Date::all();

        // Get Sent Documents for signature
        //$contracts = $this->getStudentContracts($student , $applicant->submissions);

        $school = School::byUuid(session('tenant'))->firstOrFail();

        if (config('app.locale') == 'en') {
            $datatablei18n = 'English';
        }

        switch (config('app.locale')) {
            case 'en':
                $datatablei18n = 'English';
                break;
            case 'es':
                $datatablei18n = 'Spanish';
                break;
            case 'fr':
                $datatablei18n = 'French';
                break;
            default:
                $datatablei18n = 'English';
        }

        $balance = InvoiceHelpers::getStudentBalance($student);
        $integratable = $this->inetgration();
        $messages = $applicant->receivedMessages()->reverse();
        $customFields = CustomField::where('properties' , 'students')->get();

        return view(
            'back.students.show',
            compact('applicant', 'school', 'datatablei18n', 'balance', 'courses', 'program', 'campus', 'date', 'student', 'integratable', 'request_link','permissions' , 'messages' , 'customFields')
        );
    }

    public function showApplicant(Student $student)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE,
            'delete|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE,
            'view|submission',
            'edit|submission',
            'create|submission',
            'delete|submission',
            'campusesView|submission',
            'campusesEdit|submission',
            'campusesCreate|submission',
            'campusesDelete|submission',
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', $student)) {
            return PermissionHelpers::accessDenied();
        }

        $applicant = $student->load([
            'submissions',
            'submissions.application',
            'submissions.statuses',
            'submissions.application.sections',
            'submissions.application.sections.fields',
            'contracts',
            'agent',
            'agent.agency',
            'invoices',
            'invoices.status',
            'attendances',
            'attendances.lesson',
        ]);

        switch (config('app.locale')) {
            case 'en':
                $datatablei18n = 'English';
                break;
            case 'es':
                $datatablei18n = 'Spanish';
                break;
            case 'fr':
                $datatablei18n = 'French';
                break;
            default:
                $datatablei18n = 'English';
        }

        $balance = InvoiceHelpers::getStudentBalance($student);
        $integratable = $this->inetgration();
        $messages = $applicant->receivedMessages()->reverse();
        $customFields = CustomField::where('properties', 'students')->get();

        return view(
            'back.students.show-applicant',
            compact('applicant', 'datatablei18n', 'student', 'permissions', 'balance', 'integratable' , 'messages', 'customFields')
        );
    }
    public function getDataSource(Request $request , Student $student)
    {
        switch ($request->source)
        {
            case 'contact-type':
                return SubmissionHelpers::getStagesList();
                break;

            case 'campus':
                return $this->getCampusesList();
                break;

            case 'owner':
                return SchoolHelper::getUsersList();
                break;

            default:
                return [];
                break;
        }
    }
    public function quickEdit(Request $request , Student $student)
    {
        switch ($request->name) {
            case 'contact-type':
                $student->stage = $request->value;
                return $student->save();
                break;
            case 'campus':
                $student->campuses()->sync($request->value);
                return $student->save();
                break;

            case 'owner':
            $student->owner_id = $request->value;
            return $student->save();
            break;

            case 'student':
                $student->{$request->field} = $request->value;
                return $student->save();
            break;

            default:
                $field = $request->name;
                $properties = $student->properties;
                $properties['customfields'][$field] = $request->value;
                $student->properties = $properties;
                $student->save();
                break;
        }

    }

    public function edit($id)
    {
        //
    }

    public function update(Request $request, $id)
    {
        //
    }

    public function destroy(Student $student)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', $student))
        {
            return PermissionHelpers::accessDenied();
        }
        // Delete Application
        if ($response = $student->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $student->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    /**
     * Show Student Reminder email
     *
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function showReminderEmailForm(Student $student, Invoice $invoice)
    {
        $route = 'invoice.reminder.email.send';

        return view(
            'back.students._partials.student-send-invoice-reminder-email',
            compact('student', 'invoice', 'route')
        );
    }

    /**
     * Send Payment Email
     *
     * @param Request $request
     * @return void
     */
    public function sendReminderEmail(Student $student, Invoice $invoice, Request $request)
    {
        $data = [
            'subject'                 => $request->subject,
            'body'                    => $request->body,
            'include_payment_link'    => $request->include_payment_link,
        ];

        Mail::to($student->email)->send(new SendInvoiceReminderEmail($student, $invoice, $data));

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => '', 'message' => 'Your email sent successfully'],
        ]);
    }

    public function syncStudentToMautic($payload)
    {
        if (isset($payload['student'])) {
            $this->inetgration();
        }
    }

    public function editUuid(Student $student)
    {
        $route = 'students.uuid.update';

        return view(
            'back.students._partials.uuid-edit',
            compact('student', 'route')
        );
    }

    public function updateUuid(Request $request, Student $student)
    {
        $request->validate(['uuid' => 'required']);

        $student->uuid = $request->uuid;

        $student->save();

        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => ['student_id' => $student->id],
            ]
        );
    }

    public function createAddCohort(Student $student)
    {
        $route = 'students.add.cohort';

        return view(
            'back.students._partials.cohort-create',
            compact('student', 'route')
        );
    }

    public function addToCohort(Request $request, Student $student)
    {
        $request->validate(['cohort' => 'required']);
        $group_id = $request->cohort;

        $student_groups = array_column($student->groups->toArray(), 'id');

        if (in_array($group_id, $student_groups)) {
            return Response::json(
                [
                    'status' => 409,
                    'response' => 'duplicate',
                    'extra' => [
                        'group_id' => $group_id,
                    ],
                ]
            );
        }

        $group = Group::findOrFail($group_id);

        $group->students()->attach($student);

        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => [
                    'student_id' => $student->id,
                    'group_id' => $group_id,
                    'group' => $group->title,
                    'program' => isset($group->program) ? $group->program->title : '',
                    'start_date' => $group->start_date,
                    'end_date' => $group->end_date,
                ],
            ]
        );
    }

    public function destroyFromCohort(Student $student, Group $group)
    {
        $group->students()->detach($student);

        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => [
                    'group_id' => $group->id,
                ],
            ]
        );
    }

    public function showJson(Request $request)
    {
        $student = Student::findOrfail($request->student_id);

        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => [
                    'student_id' => $request->student_id,
                    'email' => $student->email,
                    'address' => $student->fullAddress,
                ],
            ]
        );
    }

    public function showPaymentJson(Request $request)
    {
        $student = Student::findOrfail($request->student_id);

        $invoices = $student->enabledInvoices()->get();

//        $order = $payload['order'];
        $html = view('back.payment._partials.invoice-line', compact('invoices'))->render();

        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => [
                    'student_id' => $request->student_id,
                    'email' => $student->email,
                    'address' => $student->address,
                    'invoices' => $html,
                ],
            ]
        );
    }

    public function searchContacts(Request $request)
    {
        $request->validate([
            'search' => 'required',
        ]);

        $query = $request->search;
        $students = Student::where('first_name', 'like', '%'.$query.'%')
            ->orWhere('last_name', 'like', '%'.$query.'%')
            ->orWhere('email', 'like', '%'.$query.'%')
        ->get();

        return view('back.students.search-result', compact('students'));
    }

    public function showInAgent(School $school, Student $student)
    {
        $agent = Auth::guard('agent')->user();
        $agency = $agent->agency;

        $applicant = $student->load(
            'submissions',
            'submissions.application',
            'submissions.application.sections',
            'submissions.application.sections.fields',
            'invoices',
            'invoices.status',
            'attendances',
            'attendances.lesson'
        );

        $courses = Course::all();
        $program = Program::all();
        $campus = Campus::all();
        $date = Date::all();

        $school = School::byUuid(session('tenant'))->firstOrFail();

        if (config('app.locale') == 'en') {
            $datatablei18n = 'English';
        }

        switch (config('app.locale')) {
            case 'en':
                $datatablei18n = 'English';
                break;
            case 'es':
                $datatablei18n = 'Spanish';
                break;
            case 'fr':
                $datatablei18n = 'French';
                break;
            default:
                $datatablei18n = 'English';
        }

        $balance = InvoiceHelpers::getStudentBalance($student);

        return view(
            'front.agent.applicants.show',
            compact('applicant', 'school', 'datatablei18n', 'balance', 'courses', 'program', 'campus', 'date', 'agency')
        );
    }

    public function uploadImage(Request $request)
    {
        if ($fileName = Storage::putFile('/'.session('tenant'), $request->file)) {
            // Store File In Students Table
            $storedFile = Student::where('id', $request->stId)->update(['avatar' => $fileName]);

            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => [
                    'file'    => $storedFile,
                ],
            ]);
        }
    }

    public function studentsDownloadExcel(Request $request)
    {
        $file = $request->file;
        $studentIds = $request->data;
        $studentIds = explode(',', $studentIds);
        $data = [];

        $students = Student::whereIn('id', $studentIds)->get();
        foreach ($students as $student) {
            if($student->submissions->count() < 1){
                $studentData = [];
                $studentData['name'] = $student->name;
                $studentData['email'] = $student->email;
                $studentData['application'] = '';
                $studentData['application_status'] = '';
                $studentData['agent_name'] = $student->agent->name;
                $studentData['created'] = $student->created_at;
                array_push($data, $studentData);
            }
            foreach ($student->submissions as $submissions) {
                $studentData = [];
                $studentData['name'] = $student->name;
                $studentData['email'] = $student->email;
                $studentData['application'] = $submissions->application->title;
                $studentData['application_status'] = $submissions->status;
                $studentData['agent_name'] = $student->agent->name;
                $studentData['created'] = $student->created_at;
                array_push($data, $studentData);
            }
        }

        $headings = [
            __('Name'),
            __('Email'),
            __('Application'),
            __('Application Status'),
            __('Agent Name'),
            __('Created'),
        ];

        $export = new StudentsExport($data, $headings);

        $file_name = 'submissions_' . time() . '.xlsx';

        if ($file === 'csv') {
            $file_name = 'submissions_' . time() . '.csv';
        }

        return Excel::download($export, $file_name);
    }

    public function bulkDestroy(Request $request)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', null)) {
            return PermissionHelpers::accessDenied();
        }

        $students = [];
        if(isset($request->selected) and isset($request->selected[0]['selected'])){
            $students = Student::whereIn('id', array_map(fn($a) => $a['selected'], $request->selected));
        }else{
            $students = Student::whereIn('id',$request->selected);
        }

        if ($students->delete()) {
            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'data_table' => 'students_datatable',
                        'message'    => __('Deleted Successfully!')
                    ],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 419,
                    'response' => 'faild',
                    'extra'    => ['message' => 'Something went wrong!'],
                ]
            );
        }
    }


}
