<?php

namespace App\Http\Controllers\Tenant\School\Agent;

use App\Helpers\School\AgentHelpers;
use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Application;
use App\Tenant\Models\Submission;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Response;
use Session;

class HomeController extends Controller
{
    public function __construct()
    {
        $this->middleware('activeAgency');
    }

    public function index(Request $request, School $school)
    {
        $applications = AgentHelpers::getPublishedApplications(false);
        $agent = Auth::guard('agent')->user();
        $agency = $agent->agency;

        // Bookings
        $bookings = $agent
            ->agentBookings()
            ->orderBy('created_at', 'DESC')
            ->orderBy('quotation_id', 'DESC')
            ->with(['quotation', 'quotation.application', 'invoices', 'invoices.status'])->get();

        $agencyApplications = Application::agency()->with([
                'sections',
                'sections.fields',
                'agencySubmissions' => function ($q) use ($agency) {
                    return $q->where('agency_id', $agency->id);
                },
        ])->get();

        $applicationsList = [];
        foreach ($agencyApplications as $application) {
            if ($application->properties['show_in'] == 'registration') {
                $applicationsList[] = $application;
            }
        }

        // Agent Has One Agency
        $agency = $agent->agency()->get()->first();
        $students = null;
        $students = AgentHelpers::getStudentsList();

        return view(
            'front.agent.applicants.index',
            compact('school', 'applications', 'students', 'agent', 'agency', 'bookings', 'applicationsList')
        );
    }

    public function applicants(Request $request, School $school)
    {
        $applications = AgentHelpers::getApplications();
        $agent = Auth::guard('agent')->user();

        // Agent Has One Agency
        $agency = $agent->agency->first();

        // Check Filter
        /* if( !$filter = $request->session()->get('agents-applicant-hem') ){
                    $filter = [];
            }
            $students = $this->getStudentsList($filter); */

        $students = AgentHelpers::getStudentsList();

        return view('front.agent.applicants.index', compact('school', 'applications', 'students', 'agent', 'agency'));
    }

    public function submissions(Request $request, School $school)
    {
        $applications = AgentHelpers::getPublishedApplications(false);
        $agent = Auth::guard('agent')->user();
        $agency = $agent->agency;

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

        return view('front.agent.submission.index', compact('datatablei18n', 'school', 'applications', 'agent', 'agency'));
    }

    public function getSubmissions(Request $request)
    {
        $agent = Auth::guard('agent')->user();
        $students = AgentHelpers::getStudents()->get()->pluck('id')->toArray();

        //# Read value
        $draw = $request->draw;
        $row = $request->start;
        $rowperpage = $request->length; // Rows display per page
        $columnIndex = $request->order[0]['column']; // Column index
        $columnName = $request->columns[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->order[0]['dir']; // asc or desc
        $searchValue = $request->search['value']; // Search value

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
        }

        $start_date = $request->start_date;
        $end_date = $request->end_date;

        //# Total number of records without filtering
        $totalRecords = Submission::select(DB::raw('count(*) as allcount'))
            ->join('students', 'submissions.student_id', '=', 'students.id')
            ->whereIn('submissions.student_id', $students)
            ->count();

        //# Fetch records
        $submissions = Submission::with('application', 'student', 'application.invoices', 'application.invoices.status')
            //->whereIn('submissions.student_id', $students)
            ->join('students', 'submissions.student_id', '=', 'students.id')
            ->whereIn('submissions.student_id', $students)
            ->select([
                'submissions.*',
                'submissions.id AS submission_id',
                'students.id',
                'students.first_name',
                'students.last_name',
                'students.email',
                'students.stage',
            ])
            ->skip($row)
            ->take($rowperpage);

        // return view('back.submission.index', compact('submissions'));

        $totalRecordWithFilter = Submission::select('count(*) as allcount')
            ->join('students', 'submissions.student_id', '=', 'students.id')
            ->whereIn('submissions.student_id', $students);

        //# Search
        if ($start_date != '' && $end_date != '' && $end_date >= $start_date) {
            $searchDatesRange = function ($query) use ($start_date, $end_date) {
                $query->whereBetween('submissions.created_at', [$start_date, $end_date]);
            };

            $totalRecordWithFilter->where($searchDatesRange);

            $submissions->where($searchDatesRange);
        }

        if ($searchValue != '') {
            $searchQuery = function ($query) use ($searchValue) {
                $query->whereHas('student', function ($query) use ($searchValue) {
                    return $query->where('first_name', 'like', '%'.$searchValue.'%')
                        ->orWhere('last_name', 'like', '%'.$searchValue.'%')
                        ->orWhere('email', 'like', '%'.$searchValue.'%');
                });
            };

            //# Total number of record with filtering
            $totalRecordWithFilter->where($searchQuery);

            $submissions->where($searchQuery);
        }

        $submissions->orderBy($columnName, $columnSortOrder);

        $totalRecordWithFilter = $totalRecordWithFilter->count();
        $submissions = $submissions->get();
        $data = [];

        foreach ($submissions as $submission) {
            $invoices = isset($submission->application->invoices) ? $submission->application->invoices : collect([]);

            $invoice = $invoices->filter(function ($value, $key) use ($submission) {
                return $value->student_id == $submission->student->id;
            })->last();

            if ($invoice != null && isset($invoice->status) && count($invoice->status) > 0) {
                $status = $invoice->status->last()->status;
            } else {
                $status = null;
            }

            $school = School::byUuid(session('tenant'))->firstOrFail();

            $data[] = [
                'name'           => isset($submission->student->name) ? $submission->student->name : '',
                'link'           => route('agent.student.show' , ['school' => $school , 'student' => $submission->student]),
                'email'          => isset($submission->student->email) ? $submission->student->email : '',
                'application'    => isset($submission->application->title) ? $submission->application->title : '',
                'payment_status' => $status,
                'submission_status' => $submission->status,
                'student_stage'  => isset($submission->student->stage) ? ucfirst($submission->student->stage) : '',
                'updated'        => $submission['updated_at']->diffForHumans(),
                'created'        => $submission['created_at']->diffForHumans(),
                'id'             => isset($submission->student->id) ? $submission->student->id : '-1',
                'submission_id'  => isset($submission->submission_id) ? $submission->submission_id : '-1',
                'unlock_request' => isset($submission->properties['request_unlock']) && $submission->properties['request_unlock'] == 1 ? 1 : 0,
                'review'         => route('school.agent.student.submit', ['school' => $school, 'application' => $submission->application, 'student' => $submission->student]),
            ];
        }

        //# Response
        $response = [
            'draw' => intval($draw),
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordWithFilter,
            'aaData' => $data,
        ];

        //print_r($response);
        return Response::json($response);
    }

    public function finance(Request $request, School $school)
    {
        $agent = Auth::guard('agent')->user();
        $agency = $agent->agency;

        $students = AgentHelpers::getStudents()->get();

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

        return view(
            'front.agent.applicants.finance',
            compact('school', 'students', 'agent', 'agency', 'datatablei18n')
        );
    }

    public function getFilteredStudents(Request $request)
    {
        $filter = [];

        if (isset($request->payload['filterBy'])) {
            switch ($request->payload['filterBy']) {
                case 'date':
                    $filter = [
                        ['students.created_at', '>=', $request->payload['startDate']],
                        ['students.created_at', '<=', $request->payload['endDate']],
                    ];
                    break;

                case 'text':
                    $values = explode(' ', $request->payload['string']);
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

        //Session::put('agents-applicant-'.$request->school , $filter );

        $students = AgentHelpers::getStudentsList($filter);
        $applications = AgentHelpers::getApplications();

        $html = view('front.agent._partials.students', compact('students', 'applications'))->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => ['html' => $html],
        ]);
    }
}
