<?php

namespace App\Http\Controllers\Tenant;

use DB;
use Sign;
use Response;
use App\School;
use Carbon\Carbon;
use App\Filters\HasFilters;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Tenant\Models\Course;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use App\Tenant\Models\Contract;
use App\Tenant\Models\Submission;
use App\Exports\SubmissionsExport;
use App\Tenant\Models\Application;
use App\Tenant\Traits\HasCampuses;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Facades\Excel;
use App\Tenant\Models\SubmissionStatus;
use App\Events\Tenant\School\UnlockEvent;
use App\Events\Tenant\Student\StudentCreated;
use App\Helpers\Permission\PermissionHelpers;
use App\Helpers\Application\SubmissionHelpers;
use App\Helpers\Application\ApplicationHelpers;
use App\Events\Tenant\School\UnlockRequestEvent;
use App\Helpers\Application\ApplicationStatusHelpers;
use App\Events\Tenant\Application\ApplicationSubmissionApproved;
use App\Helpers\School\SchoolHelper;
use App\Tenant\Models\Campus;

class SubmissionController extends Controller
{
    use HasFilters;
    use HasCampuses;

    const  PERMISSION_BASE = "submission";


    public function __construct()
    {
        $this->middleware('plan.features:application')
            ->only(['index', 'getSubmissions', 'store', 'show', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
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

        $submissions = Submission::byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->get();

        foreach ($submissions as $submission) {
            $last_status = $submission->statuses->last();
            if (isset($last_status) && !empty($last_status)) {
                $submission->status = $last_status->status;
                $submission->timestamps = false;
                $submission->save();
            }

            if (is_null($submission->steps_progress_status)) {
                $application = $submission->application;
                $apllication_steps = 0;
                if ($application && $submission && is_array($submission->properties) && array_key_exists('step', $submission->properties)) {
                    $apllication_steps = count($application->sections);
                    $submission->steps_progress_status = 100 * $submission->properties['step'] / $apllication_steps;
                } else {
                    $submission->steps_progress_status = 0;
                }
                $submission->timestamps = false;
                $submission->save();
            }
        }


        $submission_statuses = SubmissionStatus::where('status', 'Lock')->orWhere('status', 'UnLock')->get();


        if ($submission_statuses->count()) {
            foreach ($submission_statuses as $status) {
                if ($status->status == 'Lock') {
                    $status->status = 'Locked';
                    $status->save();
                }
                if ($status->status == 'UnLock') {
                    $status->status = 'UnLocked';
                    $status->save();
                }
            }
        }
        $applications = ApplicationHelpers::getApplication();
        $statuses = ApplicationStatusHelpers::getStatusesDoubleTitle();

        $progress = [
            '0-25' => '< 25%',
            '25-50' => '25% - 50%',
            '50-75' => '50% - 750%',
            '75-100' => '75% - 100%',
        ];
        $student_statuses = ['applicant' => 'Applicant', 'student' => 'Student'];

        return view(
            'back.submission.index',
            compact('datatablei18n', 'applications', 'statuses', 'progress', 'student_statuses' , 'permissions')
        );
    }

    public function show(Submission $submission)
    {
        if (! PermissionHelpers::verifyPermission('view|submission')) {
            return redirect(route(PermissionHelpers::REDIRECTIO_ON_FAIL));
        }

        $submission_statuses = $submission->statuses;
        $application = $submission->application;

        return view(
            'back.submission.show',
            compact('submission', 'submission_statuses', 'application')
        );
    }
        /**
     * Create  new student and submission From
     *
     * @param Request $request
     * @param [type] $student
     * @return View
     */
    public function create(Request $request , $student = null)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
        return PermissionHelpers::accessDenied();
        }

        $applicationList = ApplicationHelpers::getApplicationsList('student' , true ,'slug');

        $campuses = $this->getUserCampusesList();
        return view('back.submission.create', compact('student' , 'campuses' , 'applicationList'));
    }

/**
    * Create new student and submission
    *
    * @param Request $request
    * @return Response
    */
    public function store(Request $request )
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
        return PermissionHelpers::accessDenied();
        }
        $request->validate([
            'firstname'     => ['required'],
            'lastname'      => ['required'],
            'email'         => ['email','required'],
            'application'   => ['required'],
        ]);
        // create the new student
        $password = SchoolHelper::generatePassword(8);
        $data = [
            'first_name'            => $request->firstname,
            'last_name'             => $request->lastname,
            'password'              => Hash::make($password),
            'role'                  => 'student',
            'consent'               => true,
            'owner_id'              => $this->getUser()->id,
            'stage'                 => "applicant"
        ];

        $student = Student::firstOrCreate(
            [
                'email' => $request->email
            ],
            $data
        );

        if(!$student->owner){
            $student->owner_id = $this->getUser()->id;
            $student->save();
        }

        $school = School::byUuid(session('tenant'))->first();
        $application_url = route('application.show', ['school' => $school, 'application' => $request->application]);

        // Reset the hashed password
        $data['password']   = $password;
        $data['email']      = $request->email;
        $data['language']   = 'en';
        $data['login_url'] = $application_url;

        $sendNotification   = $request->filled('send_email') ? true : false;

        // Disable Notification if the student was not recently created
        if(!$student->wasRecentlyCreated){
            $sendNotification = false;
        }


        event(new StudentCreated($student, $sendNotification, $data));

        $redirectURL = route('applicants.show' , ['student' => $student]);

        $impersonated = app(StudentController::class)->impersonate($student , $redirectURL);

        if ($impersonated) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'url'       => $application_url
            ]);

        }else{

            return Response::json([
                'status'    => 500,
                'response'  => 'failed',
                'message'   => -('Something went wrong!')
            ]);


        }
    }
    public function getSubmissions(Request $request)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }

        $permission_submission_delete = PermissionHelpers::verifyPermission('delete|submission');

        //# Read value
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request->order[0]['column']; // Column index
        $columnName = $request->columns[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->order[0]['dir']; // asc or desc
        $searchValue = $request->search['value']; // Search value
        $groupbyemail = $request->groupbyemail; // Search value

        switch ($columnName) {
            case 'name':
                $columnName = 'students.first_name';
                break;
            case 'created':
                $columnName = 'submissions.created_at';
                break;
            case 'updated':
                $columnName = 'submissions.updated_at';
                break;
            case 'progress_status':
                $columnName = 'submissions.steps_progress_status';
                break;
            case 'student_stage':
                $columnName = 'students.stage';
                break;
        }

        //# Custom Field value
        $start_date         = $request->start_date;
        $end_date           = $request->end_date;
        $applications       = $request->applications;
        $statuses           = $request->statuses;
        $progress           = $request->progress;
        $student_statuses   = $request->student_statuses;

        if($groupbyemail and $groupbyemail === "true"){

            //# Total number of records without filtering
            $totalRecordWithFilter = Student::leftJoin('submissions', 'students.id', '=', 'submissions.student_id')
            ->select('students.id', DB::raw('count(*) as count'))
            ->groupBy('students.id')
            ->where('stage', 'applicant');

            if($request->archived == 'true'){
                $totalRecordWithFilter->where('submissions.status', 'Archived');
            }else{
                $totalRecordWithFilter->where(function($query){
                    $query->where('submissions.status', '!=', 'Archived');
                    $query->orWhereNull('submissions.status');
                });
            }

            $students = Student::leftJoin('submissions', 'students.id', '=', 'submissions.student_id')
            ->leftJoin('campus_student', 'students.id', '=', 'campus_student.student_id')
            ->select([
                    'students.id as id',
                    'students.id as student_id',
                    'students.first_name',
                    'students.last_name',
                    'students.email',
                    'students.stage',
                    'campus_student.campus_id'
                ])
                ->groupBy('students.id', 'students.first_name', 'students.last_name', 'students.email');

            if($request->archived == 'true'){
                $students->where('submissions.status', 'Archived');
            }else{
                $students->where(function($query){
                    $query->where('submissions.status', '!=', 'Archived');
                    $query->orWhereNull('submissions.status');
                });
            }

            if(in_array($columnName, ['students.first_name', 'students.email']))
                $students->orderBy($columnName, $columnSortOrder);
            else
                $students->orderBy('students.id', 'desc');

            $students->skip($row)
            ->take($rowperpage);

            if ($searchValue != '') {
                $searchQuery = function ($query) use ($searchValue) {
                    return $query->where('first_name', 'like', '%'.$searchValue.'%')
                        ->orWhere('last_name', 'like', '%'.$searchValue.'%')
                        ->orWhere('email', 'like', '%'.$searchValue.'%');
                };

                //# Total number of record with filtering
                $totalRecordWithFilter->where($searchQuery);
                $students->where($searchQuery);
            }

            //# Search Date
            if ($start_date != '' && $end_date != '' && $end_date >= $start_date) {
                $start_date = $start_date . ' 00:00:00';
                $end_date = $end_date . ' 23:59:59';

                $searchDatesRange = function ($query) use ($start_date, $end_date) {
                    $query->whereBetween('submissions.created_at', [$start_date, $end_date]);
                };
                $totalRecordWithFilter->where($searchDatesRange);
                $students->where($searchDatesRange);
            }

            # filter Application
            if (isset($applications) && !empty($applications) && is_array($applications)) {
                $searchApplications = function ($query) use ($applications) {
                    $query->whereIn('submissions.application_id', array_map('intval', array_values($applications)));
                };
                $totalRecordWithFilter->where($searchApplications);
                $students->where($searchApplications);
            }

            # filter Statuses
            if (isset($statuses) && !empty($statuses) && is_array($statuses)) {
                $searchStatuses = function ($query) use ($statuses) {
                    $query->whereIn('submissions.status', $statuses);
                };
                $totalRecordWithFilter->where($searchStatuses);
                $students->where($searchStatuses);
            }

            # filter Progress
            if (isset($progress) && !empty($progress) && is_array($progress)) {
                $searchProgress = function ($query) use ($progress) {
                    foreach ($progress as $step_progress) {
                        $progress_array = explode("-", $step_progress);
                        $query->orwhere(function ($query) use ($progress_array) {
                            return $query->whereBetween('submissions.steps_progress_status', $progress_array);
                        });
                    }
                };
                $totalRecordWithFilter->where($searchProgress);
                $students->where($searchProgress);
            }

            //# filter Student_statuses
            if (isset($student_statuses) && !empty($student_statuses) && is_array($student_statuses)) {
                $searchStudent_statuses = function ($query) use ($student_statuses) {
                    $query->whereIn('stage', $student_statuses);
                };
                $totalRecordWithFilter->where($searchStudent_statuses);
                $students->where($searchStudent_statuses);
            }

            $students = $students->get();

            $totalRecordWithFilter = $totalRecordWithFilter->get();

            $data = [];

            foreach ($students as $student) {
                $lastSubmissions = Submission::where('student_id', $student->student_id)->orderBy('id', 'desc');

                # filter Statuses
                if (isset($statuses) && !empty($statuses) && is_array($statuses)) {
                    $searchStatuses = function ($query) use ($statuses) {
                        $query->whereIn('submissions.status', $statuses);
                    };
                    $lastSubmissions->where($searchStatuses);
                }

                 # filter Progress
                if (isset($progress) && !empty($progress) && is_array($progress)) {
                    $searchProgress = function ($query) use ($progress) {
                        foreach ($progress as $step_progress) {
                            $progress_array = explode("-", $step_progress);
                            $query->orwhere(function ($query) use ($progress_array) {
                                return $query->whereBetween('submissions.steps_progress_status', $progress_array);
                            });
                        }
                    };
                    $lastSubmissions->where($searchProgress);
                }

                # filter Progress
                if (isset($progress) && !empty($progress) && is_array($progress)) {
                    $searchProgress = function ($query) use ($progress) {
                        foreach ($progress as $step_progress) {
                            $progress_array = explode("-", $step_progress);
                            $query->orwhere(function ($query) use ($progress_array) {
                                return $query->whereBetween('submissions.steps_progress_status', $progress_array);
                            });
                        }
                    };
                    $lastSubmissions->where($searchProgress);
                }

                 # filter Application
                if (isset($applications) && !empty($applications) && is_array($applications)) {
                    $searchApplications = function ($query) use ($applications) {
                        $query->whereIn('submissions.application_id', array_map('intval', array_values($applications)));
                    };
                    $lastSubmissions->where($searchApplications);
                }

                $lastSubmissions = $lastSubmissions->get();

                if(count($lastSubmissions))
                    $lastSubmission = $lastSubmissions->last();
                else
                    $lastSubmission = null;

                if ($lastSubmission != null && isset($lastSubmission->statuses) && count($lastSubmission->statuses) > 0) {
                    $status = $lastSubmission->statusLast();
                } else {
                    $status = 'Account Created';
                }

                if ($status == 'Account Created') {
                    if ($lastSubmission && $lastSubmission->status) {
                        $status = ApplicationStatusHelpers::statusLabel($lastSubmission->status);
                    }
                }

                $steps_progress_status = 0;

                if($lastSubmission)
                    $steps_progress_status =  $lastSubmission->steps_progress_status;

                if ($steps_progress_status > 100) {
                    $steps_progress_status = 100;
                }

                $properties = $lastSubmission ? $lastSubmission->properties : '';
                $application = $lastSubmission ? $lastSubmission->application()->get()->last() : null;
                $submissionsCount = count($lastSubmissions) > 1 ? ' (' . count($lastSubmissions) . ')' : '';
                $programFieldName = $application ? $application->getProgramFieldName(true, true) : '';
                $courseFieldName = $application ? $application->getCourseFieldName() : '';
                $courseTitle = $lastSubmission && $application && $courseFieldName && isset($lastSubmission->data[$courseFieldName]) ? $lastSubmission->data[$courseFieldName]['courses'] : '';

                if($courseTitle)
                    $courseTitle = Course::where('slug', $courseTitle)->first()->title;

                /* $program = $programFieldName && isset($lastSubmission->data[$programFieldName]) ? $lastSubmission->data[$programFieldName] : ''; */

                $program = $programFieldName ? SubmissionHelpers::exctractProgramValueFromSubmission($programFieldName , $lastSubmission) : '';
                $campus = $student->campus_id ? Campus::find($student->campus_id) : null;

                $data[] = [
                    'select'                => view('back.lessons._partials.select-row', ['item' => $student, 'target' => 'student'])->render(),
                    'role'                  => $student->stage,
                    'campus'                => $campus ? $campus->title : '',
                    'name'                  => ($student->name ?? '') . $submissionsCount,
                    'email'                 => $student->email ?? '',
                    'recent_transaction'    => $student->recent_transaction_description,
                    'application'           => $application && isset($application->title) ? __($application->title) : '',
                    'course'                => $courseTitle,
                    'program'               => (is_array($program ) and isset($program['programs'])) ? $program['programs'] : $program,
                    'application_status'    => __($status),
                    'progress_status'       => $steps_progress_status,
                    'student_stage'         => isset($student->stage) ? ucfirst($student->stage) : '',
                    'updated'               => $lastSubmission && $lastSubmission->updated_at ? $lastSubmission->updated_at->diffForHumans() : '',
                    'created'               => $lastSubmission && $lastSubmission->created_at ? $lastSubmission->created_at->diffForHumans() : '',
                    'id'                    => isset($student) ? $student->student_id : '-1',
                    'submission_id'         => $student->submission and count($student->submission) and isset($student->submission->last()->submission_id) ? $student->submission->last()->submission_id : $student->id,
                    'unlock_request'        => isset($properties['request_unlock']) && $properties['request_unlock'] == 1 ? 1 : 0,
                    'step'                  => isset($properties['step']) ? $properties['step'] : '0',
                    'application_sections'  => $application &&  isset($application->sections_order) ? count($application->sections_order) : '0',
                    'app_text'              => __('APP STEPS'),
                    'delete_permission'     => $lastSubmission ? PermissionHelpers::checkActionPermission('submission', 'delete', $lastSubmission) : '',
                    'delete_route'   => 'sss',
                    'view_permission'     => $lastSubmission ? PermissionHelpers::checkActionPermission('submission', 'view', $lastSubmission) : '',
                    'link'                => $lastSubmission ? SubmissionHelpers::getLink($lastSubmission) : ''
                ];
            }

            $response = [
                'draw' => (int)$draw,
                'iTotalRecords' => count($totalRecordWithFilter),
                'iTotalDisplayRecords' => count($totalRecordWithFilter),
                'aaData' => $data,
            ];

            return Response::json($response);
        }else{

            $submissions = Submission::byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->with('application', 'student', 'application.invoices', 'application.invoices.status', 'statuses')
            ->rightJoin('students', 'submissions.student_id', '=', 'students.id')
            ->select([
                'submissions.*',
                'submissions.id AS id',
                'submissions.id AS submission_id',
                'students.id as student_id',
                'students.first_name',
                'students.last_name',
                'students.email',
                'students.stage',
            ])
            ->where('stage', 'applicant');

            if($request->archived == 'true'){
                $submissions->where('submissions.status', 'Archived');
            }else{
                $submissions->where(function($query){
                    $query->where('submissions.status', '!=', 'Archived');
                    $query->orWhereNull('submissions.status');
                });
            }

            $submissions->orderBy($columnName, $columnSortOrder);
            $submissions->skip($row)
            ->take($rowperpage);


        $totalRecordWithFilter = Submission::select('count(*) as allcount')
            ->byCampus($permissions['campusesView|' . self::PERMISSION_BASE])
            ->rightJoin('students', 'submissions.student_id', '=', 'students.id')
            ->where('students.stage', 'applicant');

        if($request->archived == 'true'){
                $totalRecordWithFilter->where('submissions.status', 'Archived');
        }else{
            $totalRecordWithFilter->where(function($query){
                $query->where('submissions.status', '!=', 'Archived');
                $query->orWhereNull('submissions.status');
            });
        }

        if ($searchValue != '') {
            $searchQuery = function ($query) use ($searchValue) {
                return $query->where('first_name', 'like', '%'.$searchValue.'%')
                    ->orWhere('last_name', 'like', '%'.$searchValue.'%')
                    ->orWhere('email', 'like', '%'.$searchValue.'%');
            };

            //# Total number of record with filtering
            $totalRecordWithFilter->where($searchQuery);
            $submissions->where($searchQuery);
        }

        //# Search Date
        if ($start_date != '' && $end_date != '' && $end_date >= $start_date) {
            $start_date = $start_date . ' 00:00:00';
            $end_date = $end_date . ' 23:59:59';

            $searchDatesRange = function ($query) use ($start_date, $end_date) {
                $query->whereBetween('submissions.created_at', [$start_date, $end_date]);
            };
            $totalRecordWithFilter->where($searchDatesRange);
            $submissions->where($searchDatesRange);
        }

        //# filter Application
        if (isset($applications) && !empty($applications) && is_array($applications)) {
            $searchApplications = function ($query) use ($applications) {
                $query->whereIn('submissions.application_id', array_map('intval', array_values($applications)));
            };
            $totalRecordWithFilter->where($searchApplications);
            $submissions->where($searchApplications);
        }

        //# filter Statuses
        if (isset($statuses) && !empty($statuses) && is_array($statuses)) {
            $searchStatuses = function ($query) use ($statuses) {
                $query->whereIn('submissions.status', $statuses);
            };
            $totalRecordWithFilter->where($searchStatuses);
            $submissions->where($searchStatuses);
        }

        //# filter Progress
        if (isset($progress) && !empty($progress) && is_array($progress)) {
            $searchProgress = function ($query) use ($progress) {
                foreach ($progress as $step_progress) {
                    $progress_array = explode("-", $step_progress);
                    $query->orwhere(function ($query) use ($progress_array) {
                        return $query->whereBetween('submissions.steps_progress_status', $progress_array);
                    });
                }
            };
            $totalRecordWithFilter->where($searchProgress);
            $submissions->where($searchProgress);
        }

        //# filter Student_statuses
        if (isset($student_statuses) && !empty($student_statuses) && is_array($student_statuses)) {
            $searchStudent_statuses = function ($query) use ($student_statuses) {
                $query->whereIn('stage', $student_statuses);
            };
            $totalRecordWithFilter->where($searchStudent_statuses);
            $submissions->where($searchStudent_statuses);
        }

        $totalRecordWithFilter = $totalRecordWithFilter->count();

        $submissions = $submissions->get();
        $data = [];

        foreach ($submissions as $submission) {
            if ($submission != null && isset($submission->statuses) && count($submission->statuses) > 0) {
                $status = $submission->statusLast();
            } else {
                $status = 'Account Created';
            }

            if ($status == 'Account Created') {
                if ($submission->status) {
                    $status = ApplicationStatusHelpers::statusLabel($submission->status);
                }
            }

            if(($request->archived === 'true' and $status !== 'Archived') or ($request->archived !== 'true' and $status === 'Archived')){
                continue;
            }

            $properties = $submission->properties;
            $application = $submission->application;
            $student = $submission->student;

            $select= view('back.lessons._partials.select-row', ['item' => $submission])->render();

            $steps_progress_status = $submission['steps_progress_status'];
            if (is_null($steps_progress_status)) {
                $steps_progress_status = 0;
            }

            if ($steps_progress_status > 100) {
                $steps_progress_status = 100;
            }

            $programFieldName = $application ? $application->getProgramFieldName(true , true) : '';
            $courseFieldName = $application ? $application->getCourseFieldName() : '';
            $courseTitle = $application && $courseFieldName && isset($submission->data[$courseFieldName]) ? implode('--', $submission->data[$courseFieldName]['courses']) :'';

            $program = SubmissionHelpers::exctractProgramValueFromSubmission($programFieldName , $submission);
            $courseTitle = Course::where('slug', $courseTitle)->first();

            $data[] = [
                'select'                => $select,
                'role'                  => $student->stage,
                'campus'                => $student->campus ? $student->campus->title : '',
                'name'                  => isset($student->name) ? $student->name : '',
                'email'                 => isset($student->email) ? $student->email : '',
                'recent_transaction'    => $student->recent_transaction_description,
                'application'           => isset($application->title) ? __($application->title) : '',
                'course'                => $courseTitle ? $courseTitle->title : '',
                'program'               => ($program instanceof Program) ? $program->title : $program,
                'application_status'    => __($status),
                'progress_status'       => $steps_progress_status,
                'student_stage'         => isset($student->stage) ? ucfirst($student->stage) : '',
                'updated'               => isset($submission['updated_at']) ? $submission['updated_at']->diffForHumans() : '',
                'created'               => isset($submission['created_at']) ? $submission['created_at']->diffForHumans() : '',
                'id'                    => isset($student->id) ? $student->id : '-1',
                'submission_id'         => isset($submission->submission_id) ? $submission->submission_id : $student->id,
                'unlock_request'        => isset($properties['request_unlock']) && $properties['request_unlock'] == 1 ? 1 : 0,
                'step'                  => isset($properties['step']) ? $properties['step'] : '0',
                'application_sections'  => isset($application->sections_order) ? count($application->sections_order) : '0',
                'app_text'              => __('APP STEPS'),
                'delete_permission'     => PermissionHelpers::checkActionPermission('submission', 'delete', $submission),
                'delete_route'         => isset($submission['id']) ? route('submissions.destroy' , ['submission' => $submission['id']]) : route('students.destroy' , ['student' => $student]) ,
                'deleted_element'      => 'data-submission-id',
                'view_permission'      => PermissionHelpers::checkActionPermission('submission', 'view', $submission),
                'link'                 => SubmissionHelpers::getLink($submission)
            ];
        }

        }
        //# Response
        $response = [
            'draw' => (int)$draw,
            'iTotalRecords' => $totalRecordWithFilter,
            'iTotalDisplayRecords' => $totalRecordWithFilter,
            'aaData' => $data,
        ];

        return Response::json($response);
    }


    public function bulkEdit(Request $request)
    {
       if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit',null)) {
            return PermissionHelpers::accessDenied();
        }

        // Get Statuses List
        $statuses =  ApplicationStatusHelpers::getApplicationStatusesList();
        $stages = SubmissionHelpers::getStagesList();
        $submissions = $request->selected;
        $route = 'submissions.bulk.update';
        return view(
            'back.submission._partials.bulk-edit',
            compact(
                'statuses',
                'stages',
                'submissions',
                'route',
            )
        );
    }

    public function bulkArchive(Request $request)
    {
        foreach($request->selected as $submission_id)
        {
            SubmissionHelpers::newSubmissionStatus(Submission::findOrFail($submission_id), 'Archived', null, auth()->user());
        }

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'message'    => 'Archived Successfully'
            ]
        );
    }

    public function bulkUnarchive(Request $request)
    {
        foreach($request->selected as $submission_id)
        {
            $submission = Submission::findOrFail($submission_id);
            SubmissionHelpers::newSubmissionStatus($submission, $submission->statuses[count($submission->statuses) -2]->status, null, auth()->user());
        }

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'message'    => 'Archived Successfully'
            ]
        );
    }

    public function bulkUpdate(Request $request)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesEdit|' . self::PERMISSION_BASE
        ]);

        $submissions = json_decode($request->get('submissions'), true);
        $submissions= Submission::byCampus($permissions['campusesEdit|' . self::PERMISSION_BASE])->whereIn('id', explode(",", $submissions))->with('student')->get();

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', null)) {
            return PermissionHelpers::accessDenied();
        }

        $request->validate(
            [
                'status' => 'required_without:stage',
                'stage' => 'required_without:status',
            ]
        );



        foreach ($submissions as $submission) {

            if(PermissionHelpers::checkActionPermission('submission', 'edit', $submission)){


                if ($request->filled('status')) {
                    $submission->update(['status' => $request->status ]);
                    SubmissionHelpers::newSubmissionStatus($submission, $request->status, null, auth()->user());
                }

                if ($request->filled('stage')) {
                    $submission->student()->update(['stage' => $request->stage]);
                }
            }
        }
        return Response::json(
            [
                'status' => 200,
                'response' => 'success',
                'extra' => [
                    'data_table' => 'submissions_table',
                    'message'    => __('Updated Successfully!')
                ],
            ]
        );
    }

    public function bulkDestroy(Request $request)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', null)) {
            return PermissionHelpers::accessDenied();
        }

        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesDelete|' . self::PERMISSION_BASE
        ]);

        $submissionIds = [];
        $studentIds = [];

        foreach ($request->selected as $item) {
            if(isset($item['target']) and $item['target'] == 'student')
            {
                $studentIds[] = $item['selected'];
            }else{
                $submissionIds[] = $item['selected'];
            }
        }

        $students = null;

        if($studentIds){
            $students = Student::whereIn('id', $studentIds);
            foreach ($students as $student) {
                if($student->submissions){
                    $submissionIds = array_merge($submissionIds, array_map(fn($a) => $a->id, $student->submissions));
                }
            }
        }

        $submissions = null;
        if($submissionIds)
            $submissions = Submission::byCampus($permissions['campusesDelete|' . self::PERMISSION_BASE])->whereIn('id', $submissionIds);

        if ((($submissions and $submissions->delete()) or !$submissions) and (($students and $students->delete()) or !$students)) {

            // @TODO Delete related invoices

            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'extra'    => [
                        'data_table' => 'submissions_table',
                        'message'    => __('Deleted Successfully!')
                    ],
                ]
            );
        } else {
            return Response::json(
                [
                    'status'   => 419,
                    'response' => 'faild',
                    'extra'    => [
                        'message' => __('Something went wrong!')
                    ],
                ]
            );
        }
    }

    public function destroy($submission_id)
    {

        $submission = Submission::findOrFail($submission_id);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', $submission)) {
            return PermissionHelpers::accessDenied();
        }




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

    public function completions(Request $request)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        $request->validate([
            'time'  => 'required',
            'range' => 'required',
        ]);

        $time = $request->time;
        $range = $request->range;
        $dates = explode(' - ', $range);

        if ($dates[0] > $dates[1]) {
            $whereBetween = [Carbon::now()->startOfMonth(), Carbon::now()->endOfMonth()];
        } else {
            $whereBetween = [$dates[0], $dates[1]];
        }

        $submissions = Submission::byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->whereBetween('created_at', $whereBetween)
            ->with('application', 'application.sections')->get();

        $submissions_by_dates = [];
        switch ($time) {
            case 'day':
                foreach ($submissions as $submission) {
                    $submissions_by_dates[$submission->created_at->toDateString()][] = $submission;
                }
                break;
            case 'week':
                foreach ($submissions as $submission) {
                    $submissions_by_dates[$submission->created_at->weekOfYear][] = $submission;
                }
                break;
            case 'month':
                foreach ($submissions as $submission) {
                    $submissions_by_dates[$submission->created_at->month][] = $submission;
                }
                break;
            case 'year':
                foreach ($submissions as $submission) {
                    $submissions_by_dates[$submission->created_at->year][] = $submission;
                }
                break;
        }
        $result = [];
        $total_complete = 0;
        $total_incomplete = 0;
        foreach ($submissions_by_dates as $key => $date) {
            $complete = 0;
            $incomplete = 0;
            foreach ($date as $submission) {
                if ($submission->complete) {
                    $complete++;
                    $total_complete++;
                } else {
                    $incomplete++;
                    $total_incomplete++;
                }
            }
            $result[] = ['name' => $key, 'Complete' => $complete, 'Incomplete' => $incomplete, 'total' => $complete + $incomplete];
        }

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => [
                    'total' => [
                        'complete' => $total_complete,
                        'incomplete' => $total_incomplete,
                    ],
                    'series' => $result,
                ],
            ]
        );
    }

    public function percentageCompletions()
    {

        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        $submissions = Submission::byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->get();

        $level1 = 0;
        $level2 = 0;
        $level3 = 0;
        $level4 = 0;
        $level5 = 0;
        foreach ($submissions as $submission) {
            if ($submission->steps_progress_status <= 20) {
                $level1++;
                continue;
            }

            if ($submission->steps_progress_status <= 40) {
                $level2++;
                continue;
            }

            if ($submission->steps_progress_status <= 60) {
                $level3++;
                continue;
            }

            if ($submission->steps_progress_status <= 80) {
                $level4++;
                continue;
            }

            if ($submission->steps_progress_status <= 100) {
                $level5++;
            }
        }

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => [
                    'level5' => $level1,
                    'level4' => $level2,
                    'level3' => $level3,
                    'level2' => $level4,
                    'level1' => $level5,
                ],
            ]
        );
    }

    public function totalCompletions()
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        $submissions = Submission::byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->with('application', 'application.sections')->get();

        $complete = 0;
        $incomplete = 0;
        foreach ($submissions as $submission) {
            if (! $submission->application || ! $sections = $submission->application->sections) {
                continue;
            }
            if ((isset($submission->properties['step'])) && ($submission->properties['step'] == $sections->count())) {
                $complete++;
            } else {
                $incomplete++;
            }
        }
        $total = $complete + $incomplete;

        if ($total != 0) {
            $complete_percentage = $complete * 100 / $total;
            $incomplete_percentage = $incomplete * 100 / $total;
        } else {
            $complete_percentage = 0;
            $incomplete_percentage = 0;
        }

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => [
                    'total' => $total,
                    'complete_percentage' => $complete_percentage,
                    'incomplete_percentage' => $incomplete_percentage,
                    'complete' => $complete,
                    'incomplete' => $incomplete,
                ],
            ]
        );
    }

    public function requestUnlock(Request $request)
    {
        if ($request->lock == 0) {
            $submision = Submission::findOrFail($request->submission_id);
            $properties = $submision->properties;

            $properties['request_unlock'] = 1;
            $submision->properties = $properties;
            $submision->save();

            event(new UnlockRequestEvent($submision));

            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['Id' => $request->submission_id],
            ]);
        }
    }

    public function unlock(Request $request)
    {
        $submision = Submission::findOrFail($request->submission_id);
        $student = $submision->student;

        $properties = $submision->properties;
        $properties['lock'] = 0;
        $properties['request_unlock'] = 0;
        $submision->properties = $properties;
        $submision->save();

        event(new UnlockEvent($submision, $student));

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['Id' => $request->submission_id],
        ]);
    }

    public function review(Request $request, Submission $submission)
    {

        $permissions =  PermissionHelpers::areGranted([
            'edit|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);



        $student = auth()->guard('student')->user();
        $agent = auth()->guard('agent')->user();
        $user = auth()->user();
        $can_edit =  true;

        if (! $student && ! $user && ! $agent) {
            abort(401);
        }

        if ($user && !$permissions['edit|submission']) {
            $can_edit =  false;
        }

        $application = $submission->application;
        $excludes = ['helper', 'hidden'];

        $isAdmin = true;
        $studentView = false;
        $html = view(
            'front.applications.application-layouts.rounded.review.content',
            compact('submission', 'application', 'excludes', 'isAdmin',
            'studentView' , 'can_edit' , 'permissions')
        )->render();

        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => [
                'html' => $html,
            ],
        ]);
    }

    public function approve($payload)
    {
        $submission = Submission::find($payload['submissionId']);
        if ($submission->student->id != $payload['studentId']) {
            return null;
        }
        $data = [
            'approved_by'   => 'School',
            'name'          => Auth::guard('web')->user()->name,
            'approved_at'   => Carbon::now(),
        ];
        if (SubmissionHelpers::updateSubmissionStatus($submission, 'Approved', $data)) {

            // Dispatch Application Submission Approved Event
            // @todo Add Notification, Send Email to Student
            event(new ApplicationSubmissionApproved($submission, $submission->student, $submission->application, null, null));

            // Check if Application has a e-signature action
            if (in_array('E-Signature', $submission->application->actions->pluck('action')->toArray())) {
                $extra['show_contract_cta'] = true;
            } else {
                $extra['show_contract_cta'] = false;
            }
            $extra['show_contract_cta'] = true;

            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => $extra,
            ]);
        }
    }

    /**
     * Generate Contract from a Submission
     *
     * @param Submission $submission
     * @param Request $request
     * @return void
     */
    public function generateContract($payload)
    {
        $submission = Submission::find($payload['submissionId']);
        if ($submission->student->id != $payload['studentId']) {
            return null;
        }

        $esignAction = $submission->application->actions()->where('action', 'E-Signature')->first();

        $properties = $esignAction['properties'];

        $data = $this->getMappedData($submission->data, json_decode($properties['custom_fields'], true));

        $response = Sign::generateDocumentForSignature($data, $properties['documentHash'], $esignAction, $submission);

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => $response,
        ]);
    }

    /**
     * Edit Generated Contract
     *
     * @param [type] $payload
     * @return void
     */
    public function editContract($payload)
    {
        $submission = Submission::find($payload['submissionId']);
        if ($submission->student->id != $payload['studentId']) {
            return null;
        }

        $contract = $submission->contracts()->first();
        $esignAction = $submission->application->actions()->where('action', 'E-Signature')->first();

        $properties = $esignAction['properties'];

        $data = $this->getMappedData($submission->data, json_decode($properties['custom_fields'], true));

        $response = Sign::editDocument($data, $properties['documentHash'], $esignAction, $submission, $contract);

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => $response,
        ]);
    }

    /**
     * Void Sent Contract
     */

    /**
     * Send E-Signature Contract directly
     *
     * @param [array] $payload
     * @return void
     */
    public function sendContract($payload)
    {
        $submission = Submission::find($payload['submissionId']);
        if ($submission->student->id != $payload['studentId']) {
            return null;
        }
        // get Mapped Data
        $esignAction = $submission->application->actions()->where('action', 'E-Signature')->first();

        $properties = $esignAction['properties'];

        $data = $this->getMappedData($submission->data, json_decode($properties['custom_fields'], true));

        // Send Document for Signature
        if ($response = Sign::sendDocumentForSignature($data, $properties['documentHash'], $esignAction, $submission)) {
            $properties = $submission->properties;
            $properties['esignature'] = $response;
            $submission->properties = $properties;
            $response = head($response);

            $html = view('back.students.esignature.'.$response['service'].'.button', compact('response'))->render();

            if ($submission->save()) {
                // Dispatch Contract Sent Event
                //event(new SubmissionContractSent($submission));

                return Response::json([
                    'status'    => 200,
                    'response'  => 'success',
                    'extra'     => [
                        'html' => $html,
                        'message' => __('Sent Successfully!'),
                    ],
                ]);
            }
        }
    }



    // after contract is reviewd and sent
    public function contractSent(Submission $submission, Request $request)
    {
        // Update Sumssion Status
        $properties = $submission->properties;
        $response = $request->response;
        $response['status'] = 'Sent';
        $properties['esignature'][$response['service']] = $response;
        $submission->properties = $properties;
        if ($submission->save()) {
            return view('back.students.esignature.'.$response['service'].'.sent', compact('response', $submission));
        } else {
        }
    }



    // Contract Events Webhook
    public function contractEvent(Request $request)
    {
        // Extract details to update
        $eventDetails = Sign::getEventDetails($request);
        if (empty($eventDetails)) {
            return;
        }
        // we need to ge the contract to change the status
        if (! $contract = Contract::where('uid', $eventDetails['uid'])->first()) {
            return null;
        }

        // Update Contract Status
        if ($contract->update($eventDetails)) {
            // Update Submisson Status
            switch ($contract->status) {
                case 'sent':
                    $submissionStatus = __('Contract Sent');
                    break;

                case 'completed':
                    $submissionStatus = __('Contract Signed');
                    break;

                case 'delivered':
                    $submissionStatus = __('Contract Delivered');
                    break;

                case 'declined':
                    $submissionStatus = __('Contract Declined');
                    break;

                case 'rejected':
                    $submissionStatus = __('Contract Rejected');
                    break;

                case 'created':
                    $submissionStatus = __('Contract Created');
                    break;

                case 'voided':
                    $submissionStatus = __('Contract Voided');
                    break;

                default:
                    $submissionStatus = $contract->status;
                    break;
            }

            // Update Submssion Status
            if ($contract->submission) {
                SubmissionHelpers::updateSubmissionStatus(
                    $contract->submission,
                    $submissionStatus,
                    $contract->properties
                );
            }
        }
    }

    /**
     * Resency Application to CRM
     *
     * @param [array] $payload
     * @return void
     */
    public function resync($payload)
    {
        $submission = Submission::find($payload['submissionId']);

        if ($submission->student->id != $payload['studentId']) {
            return null;
        }

        $settings = Setting::byGroup();

        try {
            if ($integrations = $submission->application->integrations) {
                foreach ($integrations as $integration) {
                    $integrationClass = 'App\\Jobs\\Tenant\\Integrations\\Integrate'.ucwords($integration->type);

                    // check if editing the full application or just a custom field
                    if (isset($payload['field'])) {
                        $action = 'editApplication';
                        $fields[] = $payload['field'];
                    } else {
                        $action = 'submitApplication';
                        $fields = [];
                    }

                    dispatch(new $integrationClass($submission->application, $submission, $submission->student, $integration, $action, $settings, $fields));



                    return Response::json([
                            'status'    => 200,
                            'response'  => 'success',
                            'extra'     => [
                                'message' => 'Contact\'s data synchronized successfully',
                            ],
                        ]);
                }
            }
        } catch (\Exception $e) {
            return Response::json([
                        'status'    => 400,
                        'response'  => 'failed',
                        'extra'     => [
                            'message' => 'Something went wrong, Please try again later',
                        ],
                    ]);
        }
    }

    protected function getMappedData($submissionData, $map)
    {
        $data = [];
        foreach ($map as $item) {
            if (isset($submissionData[$item['field']])) {
                $data[$item['Esignature_field']] = $submissionData[$item['field']];
            }
        }

        return $data;
    }

    public function showReview(Submission $submission)
    {
        return view(
            'back.submission.review',
            compact('submission')
        );
    }

    public function calculateStepsProgressStatus()
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);


        $submissions = Submission::byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->with('application')->get();

        foreach ($submissions as $submission) {
            $step = $submission->properties['step'];
            if (! $submission->application || ! $step) {
                $submission->steps_progress_status = 0;
            } else {
                $steps_progress_status = ApplicationHelpers::stepPercentageProgress($step, $submission->application);

                $steps_progress_status = $steps_progress_status > 100 ? 100 : $steps_progress_status;

                $submission->steps_progress_status = $steps_progress_status;
            }
            $submission->timestamps = false;
            $submission->save();
        }
    }



    public function submissionsDownloadIndex(Request $request)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        $file = $request->file;

        //TODO: Arreglar filtro del modal
        $submissions = Submission::byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->with('application')->groupBy('application_id')
            ->selectRaw('count(*) as total, submissions.application_id');

        // Filter Submissions
        if ($request->has('filters')) {
            $filters = json_decode($request->filters);
            $submissions = $this->filter('submission', $filters, $submissions);
        } else {
            $filters=null;
        }
        $submissions = $submissions->get();

        return view('back.submission.export.modal', compact('submissions', 'filters', 'file'));
    }

    public function submissionsDownloadExcel(Request $request)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        //# Custom Field value
        $file = $request->file;
        $filters = $request->filters;

        $submissions = Submission::byCampus($permissions['campusesView|' . self::PERMISSION_BASE])->with('application', 'student', 'application.invoices', 'application.invoices.status', 'statuses')
            ->join('students', 'submissions.student_id', '=', 'students.id')
            ->select([
                'submissions.*',
                'submissions.id AS id',
                'submissions.id AS submission_id',
                'students.id as student_id',
                'students.first_name',
                'students.last_name',
                'students.email',
                'students.stage',
            ])
            ->where('students.stage', 'applicant');
        if ($request->has('filters')) {
            $submissions = $this->filter('submission', $request->filters, $submissions);
        } else {
            $filters=null;
        }

        $submissions = $submissions->get();

        $field_names = [];
        $field_labels = [];
        $application = Application::findOrFail($request->application)->with('sections', 'sections.fields')->first();


        foreach ($application->sections as $section) {
            foreach ($section->fields as $field) {
                if (!in_array($field->name, $field_names, true)) {
                    $field_names[] = $field->name;
                    $field_labels[] = $field->label;
                }
            }
        }

        foreach ($submissions as $submission) {
            if ($submission != null && isset($submission->statuses) && count($submission->statuses) > 0) {
                $status = $submission->statusLast();
            } else {
                $status = 'Account Created';
            }

            if ($status == 'Account Created') {
                if ($submission->status) {
                    $status = ApplicationStatusHelpers::statusLabel($submission->status);
                }
            }
            $student = $submission->student;
            $application_data = [];
            foreach ($field_names as $field_name) {
                $application_data[$field_name] = isset($submission->data[$field_name]) ? (is_array($submission->data[$field_name])) ? implode(', ', array_filter($submission->data[$field_name])) : $submission->data[$field_name] :'';
            }

            $data[] = $this->array_combine_([
                'name'                  => isset($student->name) ? $student->name : '',
                'email'                 => isset($student->email) ? $student->email : '',
                'application'           => isset($application->title) ? __($application->title) : '',
                'application_status'    => __($status),
                'progress_status'       => $submission['steps_progress_status'],
                'student_stage'         => isset($student->stage) ? ucfirst($student->stage) : '',
                'updated'               => $submission['updated_at'],
                'created'               => $submission['created_at'],
            ], $application_data);
        }
        $new_label = array_map(function ($val) {
            return __($val);
        }, $field_labels);

        $headings = [
            __('Name'),
            __('Email'),
            __('Application'),
            __('Application Status'),
            __('Progress'),
            __('Student Stage'),
            __('Updated'),
            __('Created'),
        ];

        $export = new SubmissionsExport($data, array_merge($headings, $new_label));

        $file_name = 'submissions_' . time() . '.xlsx';

        if ($file === 'csv') {
            $file_name = 'submissions_' . time() . '.csv';
        }

        return Excel::download($export, $file_name);
    }

    protected function array_combine_($array1, $array2)
    {
        foreach ($array2 as $key => $value) {
            if (array_key_exists($key, $array1)) {
                $array1[$key . '_1'] = $value;
            } else {
                $array1[$key] = $value;
            }
        }

        return $array1;
    }
}
