<?php

namespace App\Http\Controllers\Tenant;

use Response;
use DebugBar\DebugBar;
use App\Tenant\Models\Group;
use Illuminate\Http\Request;
use App\Tenant\Models\Course;
use App\Tenant\Models\Lesson;
use App\Tenant\Models\Program;
use App\Tenant\Models\Student;
use App\Tenant\Models\Schedule;
use App\Tenant\Models\Attendance;
use App\Exports\AttendancesExport;
use App\Helpers\School\GroupHelpers;
use App\Helpers\School\ModelHelpers;
use App\Http\Controllers\Controller;
use Maatwebsite\Excel\Facades\Excel;
use App\Helpers\School\CampusHelpers;
use App\Helpers\School\CourseHelpers;
use App\Helpers\School\StudentHelpers;
use App\Helpers\School\SemesterHelpers;
use App\Tenant\Traits\School\Toggleable;
use App\Helpers\Application\ProgramHelpers;
use Illuminate\Database\Eloquent\Collection;
use App\Repositories\Interfaces\CampusRepositoryInterface;
use App\Repositories\Interfaces\InstructorRepositoryInterface;

class GroupController extends Controller
{
    use Toggleable;

    protected $campus_repository;
    protected $instructor_repository;

    public function __construct(
        CampusRepositoryInterface $campus_repository,
        InstructorRepositoryInterface $instructor_repository
    ) {
        $this->middleware('plan.features:sis')
            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
        $this->campus_repository = $campus_repository;
        $this->instructor_repository = $instructor_repository;
    }

    public function index()
    {
        $params = [
            'modelName' => Group::getModelName(),
        ];
        $groups = Group::with('schedules')->get();
        return view('back.groups.index', compact('groups', 'params'));
    }

    public function create()
    {
        $campuses = CampusHelpers::getCampusesInArrayOnlyTitleId(($this->campus_repository->all())->toArray());

        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();
        $programs = ProgramHelpers::getProgramInArrayOnlyTitleId();

        $students = ModelHelpers::convertFirstNameLastnameInNameAssocWithId(StudentHelpers::getStudents());

        $instructors = ModelHelpers::convertFirstNameLastnameInNameAssocWithId($this->instructor_repository->all());

        return view(
            'back.groups.create',
            compact('campuses', 'students', 'instructors', 'courses', 'programs')
        );
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required|max:50',
            'schedules'     => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'campus'        => 'required',
            'program'       => 'required',
        ]);

        $group = Group::create([
            'title'         => $request->title,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'properties'    => $request->properties == null ? [] : $request->properties,
        ]);

        $group->campus()->associate($request->campus)->save();
        $group->program()->associate($request->program)->save();
        $group->schedules()->sync($request->schedules);

        if ($request->students) {
            $group->students()->sync($request->students);
        }

        if ($request->instructors) {
            $group->instructors()->sync($request->instructors);
        }

        return redirect(route('groups.index'))
            ->with('success', "Cohort {$group->title} created successfully!");
    }

    public function show($id)
    {
        $params = [
            'modelName' => Group::getModelName(),
        ];

        $group = Group::with('campus', 'course', 'students', 'instructors')->find($id);
        $students = $group->students()->get();

        return view(
            'back.groups.show',
            compact('group', 'params', 'students')
        );
    }

    public function edit(Group $group)
    {
        $campuses = CampusHelpers::getCampusesInArrayOnlyTitleId(($this->campus_repository->all())->toArray());

        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();
        $programs = ProgramHelpers::getProgramInArrayOnlyTitleId();

        $students = ModelHelpers::convertFirstNameLastnameInNameAssocWithId(StudentHelpers::getStudents());

        $instructors = ModelHelpers::convertFirstNameLastnameInNameAssocWithId($this->instructor_repository->all());

        $schedules = $group->schedules->pluck('id')->toArray();

        return view(
            'back.groups.edit',
            compact('group', 'campuses', 'students', 'instructors', 'courses', 'programs' , 'schedules')
        );
    }

    public function update(Request $request, Group $group)
    {
        $request->validate([
            'title'         => 'required|max:50',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'campus'        => 'required',
            'program'       => 'required',
        ]);

        $group->update([
            'title'         => $request->title,
            'start_date'    => $request->start_date,
            'end_date'      => $request->end_date,
            'properties'    => $request->properties == null ? [] : $request->properties,
        ]);

        $group->campus()->associate($request->campus)->save();
        $group->program()->associate($request->program)->save();

        $group->schedules()->sync($request->schedules);

        if ($request->students) {
            $group->students()->sync($request->students);
        } else {
            $group->students()->sync([]);
        }

        if ($request->instructors) {
            $group->instructors()->sync($request->instructors);
        } else {
            $group->instructors()->sync([]);
        }

        return redirect(route('groups.index'))
            ->with('success', "Cohort {$group->title} updated successfully!");
    }

    public function destroy(Group $group)
    {
        if ($response = $group->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $group->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    public function groupOrSemester($payload)
    {
        $program = Program::findOrFail($payload['program']);

        $semesters = SemesterHelpers::programHaveSemesters($program);

        if (! $semesters) {
            $groups = GroupHelpers::getGroupsInArrayOnlyTitleId($program->groups);

            if (count($groups) > 1) {
                $html = view(
                    'back.shared._partials.field_value',
                    [
                        'data'     => $groups,
                        'name'     => 'group',
                        'label'    => 'Cohort',
                        'required' => true,
                    ]
                )->render();
            } elseif (count($groups) == 1) {
                $html = view(
                    'back.shared._partials.field_value',
                    [
                        'data'     => $groups,
                        'name'     => 'group',
                        'required' => true,
                        'attr'     => 'disabled',
                        'value'     => key($groups),
                        'label'    => 'Cohort',
                    ]
                )->render();
            } else {
                $html = '<div class="alert alert-danger">No cohort assigned to this course</div>';
            }
        } else {
            $html = view(
                'back.semesters._partials.group_or_semester'
            )->render();
        }

        return response()->json(
            [
                'status'   => 200,
                'semester' => $semesters,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    public function courseGroups($payload)
    {
        $program = Program::findOrFail($payload['program']);

        $groups = GroupHelpers::getGroupsInArrayOnlyTitleId($program->groups);

        if (count($groups) > 1) {
            $html = view(
                'back.layouts.core.forms.multi-select',
                [
                    'name'          => 'group',
                    'label'         => 'Cohort',
                    'class'         => 'ajax-form-field  program-field',
                    'required'      => true,
                    'attr'          => 'onchange=app.courseModulesGroup(this)',
                    'data'          => $groups,
                    'placeholder'   => 'Select a Cohort',
                    'value'         => '',
                ]
            )->render();
        } elseif (count($groups) == 1) {
            $html = view(
                'back.shared._partials.field_value',
                [
                    'data'     => $groups,
                    'name'     => 'group',
                    'required' => true,
                    'attr'     => 'disabled',
                    'value'     => key($groups),
                    'label'    => 'Cohort',
                ]
            )->render();
        } else {
            $html = '<div class="alert alert-danger">No cohort assigned to this program</div>';
        }

        return response()->json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    public function groupByProgram($payload)
    {
        $groups_selected = new Collection([]);
        if(!isset($payload['program'])){
            return response()->json(
                [
                    'status'    => 400,
                    'response'  => 'fail',
                ]
            );
        }
        if (is_array($payload['program'])) {
            foreach ($payload['program'] as $program_id) {
                $program = Program::findOrFail($program_id);
                $groups_selected = $groups_selected->merge($program->groups()->isActive()->get());
            }
        } else {
            $groups_selected = Program::findOrFail($payload['program'])->first()->groups()->isActive()->get();
        }
        $groups = GroupHelpers::getGroupsInArrayOnlyTitleId($groups_selected);

        $html = view('back.semesters._partials.groups-list' , compact('groups'))->render();

        return response()->json(
            [
                'status'    => 200,
                'response'  => 'success',
                'groups'    => $groups,
                'html'      => $html
            ]
        );
    }

    public function groupByCourse($payload)
    {
//        if (is_array($payload['program'])) {
//            foreach ($payload['course'] as $course_id) {
//                $course = Course::findOrFail($course_id)->first();
//            }
//        }

        $groups = GroupHelpers::getGroupsInArrayOnlyTitleId();

        return response()->json(
            [
                'status'   => 200,
                'response' => 'success',
                'groups'    => $groups,
            ]
        );
    }

    public function groupStudents(Group $group)
    {
        $students = $group->students()->get();
        return view('back.groups._partials.group.students', compact('group', 'students'));
    }

    public function groupStudent(Group $group, Student $student, $place)
    {
        $studentAttendances = $student->attendances()->get();
        return view('back.groups._partials.group.student.index', compact('group', 'student', 'studentAttendances', 'place'));
    }

    public function groupCourses(Group $group)
    {
        return view('back.groups._partials.group.courses', compact('group'));
    }

    public function groupCourse(Group $group, Course $course, $place)
    {
        $lessonsIds = $course->lessons->pluck('id')->toArray();
        $studentsIds = $group->students()->get()->pluck('id')->toArray();
        $courseAttendances = Attendance::whereIn('student_id', $studentsIds)->whereIn('lesson_id', $lessonsIds)->get();

        return view('back.groups._partials.group.course.index', compact('group', 'course', 'courseAttendances', 'place'));
    }

    public function groupLessons(Group $group)
    {
        return view('back.groups._partials.group.attendances', compact('group'));
    }

    public function groupLesson(Group $group, Lesson $lesson, $place)
    {
        if($place == 'view_lesson_attendances') {
            return view('back.groups._partials.group.lesson.view-attendances', compact('group', 'lesson', 'place'));
        } elseif($place == 'edit_lesson_attendances') {
            return view('back.groups._partials.group.lesson.edit-attendances', compact('group', 'lesson', 'place'));
        } else {
            return view('back.groups._partials.group.attendances', compact('group'));
        }
    }

}
