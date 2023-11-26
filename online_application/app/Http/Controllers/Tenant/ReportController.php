<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\Helpers\Permission\PermissionHelpers;
use App\Http\Requests\ReportRequest;
use App\Tenant\Models\Report;
use App\Tenant\Models\ReportCategory;
use App\Tenant\Traits\HasCampuses;
use Illuminate\Http\Request;
use Response;
use DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\StudentsExport;
use App\Helpers\School\CourseHelpers;
use App\Helpers\School\StudentHelpers;
use App\Tenant\Models\Student;

class ReportController extends Controller
{
    use  HasCampuses;

    private $manyToManyRelations = [
        'students' => [
            [
                'target' => 'groups',
                'link' => 'group_student'
            ]
        ]
    ];

    const  PERMISSION_BASE = "report";

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // get User Permissions
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'campusesView|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'edit|' . self::PERMISSION_BASE,
            'delete|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'view', null)) {
            return PermissionHelpers::accessDenied();
        }

        $reports = Report::orderBy('id', 'DESC')->get();

        $params = [
            'modelName' => 'reports',
        ];

        return view('back.reports.index', compact('reports', 'permissions', 'params'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $reportCategories = ReportCategory::pluck('name', 'id')->all();

        $tables = [
            'students'      => 'Students',
            'applications'  => 'Applications',
            'courses'       => 'Courses',
            'programs'      => 'Programs',
            'submissions'   => 'Submissions'
        ];

        $campuses = $permissions['campusesCreate|' . self::PERMISSION_BASE] ? $this->getCampusesList() : $this->getUserCampusesList();

        return view('back.reports.create', compact('reportCategories', 'tables', 'campuses'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(ReportRequest $request)
    {
        //
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'create', null)) {
            return PermissionHelpers::accessDenied();
        }

        $report = new Report;
        $report->name = $request->name;
        $report->source = $request->source;
        $report->category_id = $request->category;
        $report->description = $request->description;
        $details = ['columns' => $request->columns];
        $filters = [];
        $columns = [];
        $conditions = [];
        $values = $request->value;

        $i = 1;
        for (;;) {
            $colId = 'column-' . $i;
            $conditionId = 'condition-' . $i;
            if (isset($request->$colId)) {
                $columns[] = $request->$colId;
                $conditions[] = $request->$conditionId;
            } else {
                break;
            }
            $i += 1;
        }

        if ($columns) {
            for ($i = 0; $i < count($columns); $i++) {
                $filters[] = [
                    'column' => $columns[$i],
                    'condition' => $conditions[$i],
                    'value' => $values[$i],
                ];
            }
        }

        $details['filters'] = $filters;
        $details['dates']  = array_values(array_filter(array_keys($this->getColumns($report->source)), fn ($a) => str_starts_with(explode(',', $a)[1], 'timestamp')));


        $report->filters = json_encode($details);
        $report->save();

        return redirect(route('reports.show', $report));
    }

    private function getColumnLabel(string $table, string $column): string
    {
        $columnNames = [
            'uuid' => 'user unique id',
            'created_at' => 'Creation Date',
            'stage' => 'Contact Type',
        ];

        $tableColumnNames = [
            'student.stage' => 'contact type'
        ];

        if (isset($columnNames[$column]))
            return ucwords($table . ' ' . $columnNames[$column]);

        if (isset($tableColumnNames[$column]))
            return ucwords($table . ' ' . $tableColumnNames[$column]);

        return ucwords(str_replace('_', ' ', $table)) . ' ' . ucwords(str_replace('_', ' ', $column));;
    }

    public function getStudentAttendanceReport(Student $student)
    {
        $data = DB::connection('tenant')->select("SELECT `a`.`student_id` AS `student_id`, `c`.`title` AS `course_name`, COUNT(`a`.`lesson_id`) AS `lesson_count`, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`l`.`end_time`, `l`.`start_time`)))) AS `lesson_time`,
        SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(STR_TO_DATE(`a`.`left_at`, '%H:%i:%s'), STR_TO_DATE(`a`.`attended_at`, '%H:%i:%s'))))) AS `attendence_time`,
        
        SEC_TO_TIME(SUM((TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`l`.`end_time`, `l`.`start_time`),
        TIMEDIFF(STR_TO_DATE(`a`.`left_at`, '%H:%i:%s'), STR_TO_DATE(`a`.`attended_at`, '%H:%i:%s'))))))) AS `absence_time`
        
        FROM `attendances` AS `a` INNER JOIN `lessons` AS `l` ON `a`.`lesson_id` = `l`.`id`
        INNER JOIN `courses` AS `c` ON `l`.`course_id` = `c`.`id`
        GROUP BY `c`.`title`, `a`.`student_id` HAVING `a`.`student_id` = " . $student->id);
        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();
        $students = StudentHelpers::getStudentsInArrayOnlyNameId();
        return view('back.reports.attendance', compact('student', 'data', 'courses', 'students'));
    }

    public function getAttendanceTable($payload)
    {
        $data = DB::connection('tenant')->select("SELECT `a`.`student_id` AS `student_id`, `c`.`id` AS `course_id`, `c`.`title` AS `course_name`, COUNT(`a`.`lesson_id`) AS `lesson_count`, SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(`l`.`end_time`, `l`.`start_time`)))) AS `lesson_time`,
        SEC_TO_TIME(SUM(TIME_TO_SEC(TIMEDIFF(STR_TO_DATE(`a`.`left_at`, '%H:%i:%s'), STR_TO_DATE(`a`.`attended_at`, '%H:%i:%s'))))) AS `attendence_time`,
        SEC_TO_TIME(SUM((TIME_TO_SEC(TIMEDIFF(TIMEDIFF(`l`.`end_time`, `l`.`start_time`),
        TIMEDIFF(STR_TO_DATE(`a`.`left_at`, '%H:%i:%s'), STR_TO_DATE(`a`.`attended_at`, '%H:%i:%s'))))))) AS `absence_time`
        FROM `attendances` AS `a` INNER JOIN `lessons` AS `l` ON `a`.`lesson_id` = `l`.`id`
        INNER JOIN `courses` AS `c` ON `l`.`course_id` = `c`.`id`
        GROUP BY `c`.`id`, `c`.`title`, `a`.`student_id` HAVING `a`.`student_id` = " . $payload['student'] . " AND `c`.`id` IN (" . $payload['course'] . ")");
        $html = view('back.reports._partials.attendance-table', compact('data'))->render();
        
        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => ['html' => $html],
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tenant\Models\Report $report
     * @return \Illuminate\Http\Response
     */
    public function show(Report $report, Request $request)
    {

        $builtinReportCategory = ReportCategory::where('name', 'Default')->first();
        $builtIn = false;
        $data = [];

        if ($builtinReportCategory && $report->category_id == $builtinReportCategory->id) {
            $builtIn = true;
            $data = DB::connection('tenant')->select(base64_decode($report->query));
            // dd($data);
            return view('back.reports.show', compact('report', 'data', 'builtIn'));
        }

        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $selectedReportDate = $request->reportDate;

        $reportData = $this->generateReport($report, $startDate, $endDate, $selectedReportDate);

        $data = $reportData['data'];
        $columns = $reportData['columns'];

        $filters = json_decode($report->filters);
        $reportDates = [];
        if (isset($filters->dates)) {
            foreach ($filters->dates as $reportDate) {
                $columnFullName = explode('.', explode(',', $reportDate)[0]);
                $reportDates[$reportDate] = $this->getColumnLabel($columnFullName[0], $columnFullName[1]);;
            }
        }

        return view('back.reports.show', compact('columns', 'data', 'report', 'reportDates', 'selectedReportDate', 'startDate', 'endDate'));
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Tenant\Models\Report $report
     * @param  string $startDate
     * @param  string $endDate
     * @param  string $selectedReportDate
     * @return array
     */
    private function generateReport(Report $report, ?string $startDate, ?string $endDate, ?string $selectedReportDate)
    {
        $campusTables = ['applications', 'courses', 'instructors', 'programs', 'students'];
        $selectedReportDateField = explode(',', $selectedReportDate)[0];
        $columns = [];
        $properties = [];

        foreach (json_decode($report->filters)->columns as $column) {
            $columnData = explode(',', $column);
            if (str_starts_with($columnData[1], 'json')) {
                $properties[] = $columnData[0];
                if (!in_array('properties', $columns)) {
                    $columns[] = explode('.', $columnData[0])[0] . '.properties';
                }
            } else {
                $columns[] = $columnData[0];
            }
        }

        $tables = [];

        foreach ($columns as $column) {
            if (str_contains($column, '.')) {
                $table = explode('.', $column)[0];
                if (!in_array($table, $tables) and $table != $report->source) {
                    $tables[] = $table;
                }
            }
        }

        foreach (json_decode($report->filters)->filters as $filter) {
            $filterTable = explode('.', explode(',', $filter->column)[0])[0];
            if (!in_array($filterTable, $tables) and $filterTable !== $report->source) {
                $tables[] = $filterTable;
            }
        }

        $data = DB::connection('tenant')->table($report->source);
        if ($tables) {
            foreach ($tables as $table) {
                if (isset($this->manyToManyRelations[$report->source]) and in_array($table, array_map(fn ($a) => $a['target'], $this->manyToManyRelations[$report->source]))) {
                    foreach ($this->manyToManyRelations[$report->source] as $link) {
                        $data->join($link['link'], $report->source . '.id', '=', $link['link'] . '.' . substr($report->source, 0, -1) . '_id');
                        $data->join($link['target'], $link['link'] . '.' . substr($link['target'], 0, -1) . '_id', '=', substr($link['target'], 0, -1) . '_id');
                    }
                } else {
                    $data->join($table, $report->source . '.' . substr($table, 0, -1) . '_id', '=', $table . '.id');
                }

                if (in_array($table, $campusTables)) {
                    $data->join('campus_' . substr($table, 0, -1), 'campus_' . substr($table, 0, -1) . '.' . substr($table, 0, -1) . '_id', '=', $table . '.id');
                }
            }
        }

        if (in_array($report->source, $campusTables)) {
            $data->join('campus_' . substr($report->source, 0, -1), 'campus_' . substr($report->source, 0, -1) . '.' . substr($report->source, 0, -1) . '_id', '=', $report->source . '.id');
        }

        $data = $data->select(array_map(fn ($a) => $a . ' AS ' . $a, $columns));

        foreach (json_decode($report->filters)->filters as $filter) {
            $value = $filter->value;
            $field = explode(',', $filter->column)[0];
            $dataType = explode(',', $filter->column)[1];
            $condition = $filter->condition;

            if ($field === $selectedReportDateField)
                continue;

            switch ($condition) {
                case 'starts':
                    $value = '%' . $value;
                    $condition = 'like';
                    break;
                case 'ends':
                    $value = $value . '%';
                    $condition = 'like';
                    break;
                case 'contains':
                    $value = '%' . $value . '%';
                    $condition = 'like';
                    break;
                case 'isnull':
                    $data->whereNull($field);
                    continue 2;
                    break;
                case '>=' or '>':
                    if ($dataType == 'timestamp') {
                        $value = (new \DateTime($value))->setTime(00, 00, 00);
                    }
                    break;
                case '<=' or '<':
                    if ($dataType == 'timestamp') {
                        $value = (new \DateTime($value))->setTime(23, 59, 59);
                    }
                    break;
                default:
                    break;
            }

            $data->where($field, $condition, $value);
        }

        if ($report->campuses) {
            foreach ($tables as $table) {
                if (in_array($table, $campusTables)) {
                    $data->whereIn('campus_' . substr($table, 0, -1) . '.campus_id', json_decode($report->campuses));
                }
            }

            if (in_array($report->source, $campusTables)) {
                $data->whereIn('campus_' . substr($report->source, 0, -1) . '.campus_id', json_decode($report->campuses));
            }
        }

        if ($selectedReportDateField and $startDate and $endDate) {
            $data->where($selectedReportDateField, '>=', (new \DateTime($startDate))->setTime(00, 00, 00));
            $data->where($selectedReportDateField, '<=', (new \DateTime($endDate))->setTime(23, 59, 59));
        }

        $data = $data->get()->map(function ($row) use ($properties) {
            $row = (array)$row;
            $formattedData = [];
            foreach ($row as $key => $col) {
                if (explode('.', $key)[1] === 'properties') {
                    $newKey = array_filter($properties, fn ($a) => explode('.', $a)[0] == explode('.', $key)[0])[0];
                    $newKeyName = explode('.', $newKey)[2];
                    $formattedData[$newKey] = isset(json_decode($col)->$newKeyName) ? json_decode($col)->$newKeyName : '';
                } else {
                    $formattedData[$key] = $col;
                }
            }
            return $formattedData;
        });

        $columns = array_merge(array_filter($columns, fn ($a) => explode('.', $a)[1] !== 'properties'), $properties);

        return [
            'data' => $data,
            'columns' => $columns
        ];
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function edit(Report $report)
    {
        //
        $permissions =  PermissionHelpers::areGranted([
            'view|' . self::PERMISSION_BASE,
            'create|' . self::PERMISSION_BASE,
            'campusesCreate|' . self::PERMISSION_BASE
        ]);

        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', null)) {
            return PermissionHelpers::accessDenied();
        }

        $reportCategories = ReportCategory::pluck('name', 'id')->all();

        $tables = [
            'students'      => 'Students',
            'applications'  => 'Applications',
            'courses'       => 'Courses',
            'programs'      => 'Programs',
            'submissions'   => 'Submissions'
        ];

        $conditions = [
            '=' => 'Equals to',
            'starts' => 'Starts with',
            "ends" => 'Ends with',
            "contains" => 'Contains',
            '>' => 'Greater than',
            '<' => 'Lesser than',
            '!=' => 'Not equal to',
            'isnull' => 'Is Null'
        ];

        $selectedColumns = json_decode($report->filters)->columns;
        $i = count(json_decode($report->filters)->filters);
        $columns = $this->getColumns($report->source);
        $campuses = $permissions['campusesCreate|' . self::PERMISSION_BASE] ? $this->getCampusesList() : $this->getUserCampusesList();

        return view('back.reports.edit', compact('reportCategories', 'tables', 'columns', 'conditions', 'selectedColumns', 'campuses', 'report', 'i'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function getReportFilter(Report $report)
    {
        //
        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['filters' => json_decode($report->filters)->filters]
            ]
        );
    }

    /**
     * get table columns
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function getTableColumns(Request $request)
    {
        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['columns' => $this->getColumns($request->table)],
            ]
        );
    }

    /**
     * Show add filter
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function addFilter($data)
    {
        //
        $html = view('back.reports._partials.add-filter', ['columns' => $this->getColumns($data['dataSource']), 'id' => $data['currentId']])->render();
        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  string  $table
     * @return array
     */
    private function getColumns($table)
    {
        $dbColumns = DB::connection('tenant')->select('DESCRIBE `' . $table . '`');

        $excludedColumns = ['updated_at', 'password', 'remember_token', 'deleted_at', 'updated_at', 'parent_id'];

        $columns = [];
        foreach ($dbColumns as $key => $value) {

            if (str_ends_with($value->Field, '_id') and !in_array($value->Field, $excludedColumns) and ($table !== 'students' or ($table === 'students' and $value->Field !== 'agent_id'))) {
                $value->Field = str_replace('_id', '', $value->Field);
                $dbSubColumns = DB::connection('tenant')->select('DESCRIBE `' . $value->Field . 's`');

                foreach ($dbSubColumns as $key => $subValue) {
                    if (!in_array($subValue->Field, $excludedColumns) and !str_ends_with($subValue->Field, '_id')) {
                        $columns[$value->Field . 's.' . $subValue->Field . ',' . $subValue->Type] = $this->getColumnLabel($value->Field, $subValue->Field);
                    }
                }
            } elseif ($value->Field === 'properties') {
                $row = DB::connection('tenant')->table($table)->first();
                foreach (array_keys((array)json_decode($row->properties)) as $key => $property) {
                    $columns[$table . '.properties.' . $property . ',json'] = $this->getColumnLabel($table, $property);
                }
            } else {
                if (!in_array($value->Field, $excludedColumns)) {
                    $columns[$table . '.' . $value->Field . ',' . $value->Type] = $this->getColumnLabel($table, $value->Field);
                }
            }
        }

        if (isset($this->manyToManyRelations[$table])) {
            foreach ($this->manyToManyRelations[$table] as $item) {
                $m2m = DB::connection('tenant')->select('DESCRIBE `' . $item['target'] . '`');
                foreach ($m2m as $key => $value) {

                    if (!in_array($value->Field, $excludedColumns)) {
                        $columns[$item['target'] . '.' . $value->Field . ',' . $value->Type] = $this->getColumnLabel($item['target'], $value->Field);
                    }
                }
            }
        }

        return $columns;
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function update(ReportRequest $request, Report $report)
    {
        //
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'edit', null)) {
            return PermissionHelpers::accessDenied();
        }

        $report->name = $request->name;
        $report->source = $request->source;
        $report->category_id = $request->category;
        $report->description = $request->description;
        $report->campuses = $request->campuses;
        $details = ['columns' => $request->columns];
        $filters = [];
        $columns = [];
        $conditions = [];
        $values = [];

        for ($i = 0; $i < $request->current_id; $i++) {
            $colId = 'column-' . $i;
            $conditionId = 'condition-' . $i;
            $valueId = 'value-' . $i;
            if (!isset($request->$colId)) continue;

            $columns[] = $request->$colId;
            $conditions[] = $request->$conditionId;
            $values[] = $request->$valueId;
        }

        if ($columns) {
            for ($i = 0; $i < count($columns); $i++) {
                $filters[] = [
                    'column' => $columns[$i],
                    'condition' => $conditions[$i],
                    'value' => $values[$i],
                ];
            }
        }

        $details['filters'] = $filters;
        $report->filters = json_encode($details);
        $report->save();

        return redirect(route('reports.show', $report));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Report  $report
     * @return \Illuminate\Http\Response
     */
    public function destroy(Report $report)
    {
        //
        $response = $report->delete();

        if ($response) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $report->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    public function bulkDestroy(Request $request)
    {
        if (!PermissionHelpers::checkActionPermission(self::PERMISSION_BASE, 'delete', null)) {
            return PermissionHelpers::accessDenied();
        }

        $reports = [];
        if (isset($request->selected) and isset($request->selected[0]['selected'])) {
            $reports = Report::whereIn('id', array_map(fn ($a) => $a['selected'], $request->selected));
        } else {
            $reports = Report::whereIn('id', $request->selected);
        }

        if ($reports->delete()) {
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

    /**
     * Download Excel.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  \App\Tenant\Models\Report $report
     * @return \Illuminate\Http\Response
     */
    public function reportsDownloadExcel(Request $request, Report $report)
    {
        $file = $request->file;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $selectedReportDate = $request->reportDate;

        $reportData = $this->generateReport($report, $startDate, $endDate, $selectedReportDate);

        $export = new StudentsExport($reportData['data']->toArray(), array_map(fn ($a) => $this->getColumnLabel(explode('.', $a)[0], explode('.', $a)[1]), $reportData['columns']));

        $file_name = 'report_' . time() . '.xlsx';

        if ($file === 'csv') {
            $file_name = 'report_' . time() . '.csv';
        }

        return Excel::download($export, $file_name);
    }
}
