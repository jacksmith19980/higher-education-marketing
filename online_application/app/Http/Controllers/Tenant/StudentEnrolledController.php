<?php

namespace App\Http\Controllers\Tenant;

use App\Exports\SubmissionsExport;
use App\Filters\HasFilters;
use App\Helpers\Permission\PermissionHelpers;
use App\Http\Controllers\Controller;
use App\Tenant\Models\Application;
use Maatwebsite\Excel\Facades\Excel;
use App\Tenant\Models\Course;
use App\Tenant\Models\Group;
use App\Tenant\Models\Program;
use App\Tenant\Models\Student;
use Symfony\Component\HttpFoundation\Request;
use Response;

class StudentEnrolledController extends Controller
{
    use HasFilters;

    const  PERMISSION_BASE = "student";

    public function __construct()
    {
        $this->middleware('plan.features:sis');
    }

    public function index()
    {
        $params = [
            'modelName' => 'students',
        ];

        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
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

        $programs = Program::pluck('title', 'id')->toArray();
        $groups = Group::pluck('title', 'id')->toArray();
        $courses = Course::pluck('title', 'id')->toArray();

        $students = Student::with('groups.course')->stageStudents()->orderLastName()->get();

        return view('back.enrolled.index', compact('students', 'params', 'permissions', 'datatablei18n', 'groups', 'courses', 'programs'));
    }

    public function list(Request $request)
    {
        $params = [
            'modelName' => 'students',
        ];

        $row             = $request->start;
        $rowperpage      = $request->length; // Rows display per page
        $columnIndex     = $request->order[0]['column']; // Column index
        $columnName      = $request->columns[$columnIndex]['data']; // Column name
        $columnSortOrder = $request->order[0]['dir']; // asc or desc
        $searchValue     = $request->search['value']; // Search value

        $start_date      = $request->start_date;
        $end_date        = $request->end_date;
        $courses         = $request->courses;
        $programs        = $request->programs;
        $groups          = $request->groups;

        switch ($columnName) {
            case 'name':
                $columnName = 'students.first_name';
                break;
            case 'created':
                $columnName = 'students.created_at';
                break;
        }

        $students = Student::stageStudents();
        $totalRecordWithFilter = Student::stageStudents();

        // groups filter
        if (isset($groups) && !empty($groups) && is_array($groups)) {
            $students->whereHas('groups', function ($q) use ($groups) {
                $q->whereIn('groups.id', $groups);
            });

            $totalRecordWithFilter->whereHas('groups', function ($q) use ($groups) {
                $q->whereIn('groups.id', $groups);
            });
        }

        // programs filter
        if (isset($programs) && !empty($programs) && is_array($programs)) {
            $students->whereHas('groups', function ($q) use ($programs) {
                $q->whereIn('groups.program_id', $programs);
            });

            $totalRecordWithFilter->whereHas('groups', function ($q) use ($programs) {
                $q->whereIn('groups.program_id', $programs);
            });
        }

        //# Search Date
        if ($start_date != '' && $end_date != '' && $end_date >= $start_date) {
            $start_date = $start_date . ' 00:00:00';
            $end_date = $end_date . ' 23:59:59';

            $searchDatesRange = function ($query) use ($start_date, $end_date) {
                $query->whereBetween('students.created_at', [$start_date, $end_date]);
            };
            $totalRecordWithFilter->where($searchDatesRange);
            $students->where($searchDatesRange);
        }

        if ($searchValue != '') {
            $searchQuery = function ($query) use ($searchValue) {
                return $query->where('first_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('last_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%');
            };

            //# Total number of record with filtering
            $totalRecordWithFilter->where($searchQuery);
            $students->where($searchQuery);
        }

        $students->orderBy($columnName, $columnSortOrder)
            ->skip($row)
            ->take($rowperpage);

        $students = $students->get();
        $totalRecordWithFilter = $totalRecordWithFilter->count();

        $totalRecords = Student::stageStudents()->count();

        $data = [];

        foreach ($students as $student) {

            $select = view('back.lessons._partials.select-row', ['item' => $student])->render();

            $groups = [];
            $courses = [];
            $programs = [];
            $start_date = [];
            $end_date = [];

            foreach ($student->groups as $group) {

                if ($group->program) {
                    $programs[] = $group->program->title;
                }

                if ($group->course) {
                    $courses[] = $group->course->title;
                }

                $groups[] = $group->title;
                $start_date[] = $group->start_date;
                $end_date[] = $group->end_date;
            }

            $data[] = [
                'select'                => $select,
                'id'                    => $student->id,
                'name'                  => $student->name,
                'email'                 => $student->email,
                'course'                => implode(' - ', $courses),
                'program'               => implode(' - ', $programs),
                'cohort'                => implode(' - ', $groups),
                'startDate'             => implode(' - ', $start_date),
                'endDate'               => implode(' - ', $end_date),
                'created'               => $student->created_at->diffForHumans(),
                'delete_permission'     => PermissionHelpers::checkActionPermission('student', 'delete', $student),
                'view_permission'       => PermissionHelpers::checkActionPermission('student', 'view', $student),
            ];
        }

        return Response::json([
            'draw' => $request->draw,
            'iTotalRecords' => $totalRecords,
            'iTotalDisplayRecords' => $totalRecordWithFilter,
            'aaData' => $data,
        ]);
    }

    public function studentsDownloadExcel(Request $request)
    {
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE
        ]);

        // Custom Field value
        $file = $request->file;
        $searchValue     = $request->search; // Search value
        $start_date      = $request->start_date;
        $end_date        = $request->end_date;
        $courses         = $request->courses;
        $programs        = $request->programs;
        $groups          = $request->groups;

        $students = Student::stageStudents();

        // groups filter
        if (isset($groups) && !empty($groups) && is_array($groups)) {
            $students->whereHas('groups', function ($q) use ($groups) {
                $q->whereIn('groups.id', $groups);
            });
        }

        // programs filter
        if (isset($programs) && !empty($programs) && is_array($programs)) {
            $students->whereHas('groups', function ($q) use ($programs) {
                $q->whereIn('groups.program_id', $programs);
            });
        }

        //# Search Date
        if ($start_date != '' && $end_date != '' && $end_date >= $start_date) {
            $start_date = $start_date . ' 00:00:00';
            $end_date = $end_date . ' 23:59:59';

            $searchDatesRange = function ($query) use ($start_date, $end_date) {
                $query->whereBetween('students.created_at', [$start_date, $end_date]);
            };
            $students->where($searchDatesRange);
        }

        if ($searchValue != '') {
            $searchQuery = function ($query) use ($searchValue) {
                return $query->where('first_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('last_name', 'like', '%' . $searchValue . '%')
                    ->orWhere('email', 'like', '%' . $searchValue . '%');
            };

            $students->where($searchQuery);
        }

        $students = $students->get();

        $data = [];

        foreach ($students as $student) {

            $groups = [];
            $courses = [];
            $programs = [];
            $start_date = [];
            $end_date = [];

            foreach ($student->groups as $group) {

                if ($group->program) {
                    $programs[] = $group->program->title;
                }

                if ($group->course) {
                    $courses[] = $group->course->title;
                }

                $groups[] = $group->title;
                $start_date[] = $group->start_date;
                $end_date[] = $group->end_date;
            }

            $data[] = [
                'id'                    => $student->id,
                'name'                  => $student->name,
                'email'                 => $student->email,
                'course'                => implode(' - ', $courses),
                'cohort'                => implode(' - ', $groups),
                'program'               => implode(' - ', $programs),
                'startDate'             => implode(' - ', $start_date),
                'endDate'               => implode(' - ', $end_date),
                'created'               => $student->created_at->diffForHumans()
            ];
        }

        $headings = [
            __('Id'),
            __('Name'),
            __('Email'),
            __('Course'),
            __('Cohort'),
            __('Program'),
            __('Start Date'),
            __('End Date'),
            __('Created'),
        ];

        $export = new SubmissionsExport($data, $headings);

        if (!in_array($file, ['csv', 'xlsx'])) {
            return response()->json(
                [
                    'status'        => 400,
                    'response'      => 'error',
                    'message'   => 'Invalid file type.',
                ]
            );
        }

        return Excel::download($export, 'students_' . time() . '.' . $file);
    }
}
