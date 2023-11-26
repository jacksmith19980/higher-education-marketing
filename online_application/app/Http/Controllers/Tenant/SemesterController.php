<?php

namespace App\Http\Controllers\Tenant;

use App\Helpers\Application\ProgramHelpers;
use App\Helpers\School\CourseHelpers;
use App\Helpers\School\GroupHelpers;
use App\Helpers\School\SemesterHelpers;
use App\Http\Controllers\Controller;
use App\Tenant\Models\Course;
use App\Tenant\Models\Group;
use App\Tenant\Models\Program;
use App\Tenant\Models\Semester;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Response;

class SemesterController extends Controller
{
    public function __construct()
    {
        $this->middleware('plan.features:application')
            ->only(['index', 'create', 'store', 'show', 'edit', 'update', 'destroy']);
    }

    public function index()
    {
        $params = [
            'modelName'   => Semester::getModelName(),
        ];

        $semesters = Semester::all();

        return view('back.semesters.index', compact('semesters', 'params'));
    }

    public function create()
    {
        $programs = ProgramHelpers::getProgramInArrayOnlyTitleId();
        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();

        return view('back.semesters.create', compact('programs', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'title'         => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'groups'        => 'required',
        ]);

        try {
            $semester = Semester::create([
                'title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            if (isset($request->program) && count($request->program) > 0) {
                foreach ($request->program as $program_id) {
                    $program = Program::findOrFail($program_id);
                    $program->semestereable()->save($semester);
                }
            }

            if (isset($request->course) && count($request->course) > 0) {
                foreach ($request->course as $course_id) {
                    $course = Course::findOrFail($course_id);
                    $course->semestereable()->save($semester);
                }
            }

            foreach ($request->groups as $group_id) {
                $group = Group::findOrFail($group_id);
                $semester->groups()->save($group);
            }

            $message = ['success' => "Semester {$semester->title } created successfully!"];
        } catch (\Exception $e) {
            $message['error'] = $e->getMessage();
        }

        return redirect(route('semesters.index'))
            ->with($message);
    }

    public function edit($id)
    {
        $semester = Semester::findOrFail($id);
        $programs = ProgramHelpers::getProgramInArrayOnlyTitleId();
        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();

        $groups_selected = Arr::pluck($semester->groups->toArray(), 'title', 'id');

        $programs_selected = Arr::pluck($semester->programs->toArray(), 'title', 'id');
        $courses_selected = Arr::pluck($semester->courses->toArray(), 'title', 'id');

        $groups = [];
        foreach ($semester->programs as $program) {
            $groups = array_merge($groups, $program->groups->toArray());
        }

        $groups_of_selected_program = GroupHelpers::getGroupsInArrayOnlyTitleId($groups);

        return view('back.semesters.edit', compact(
            'semester',
            'programs',
            'courses',
            'groups_selected',
            'groups_of_selected_program',
            'programs_selected',
            'courses_selected',
        ));
    }


    public function update(Request $request, Semester $semester)
    {
        $request->validate([
            'title'         => 'required',
            'start_date'    => 'required',
            'end_date'      => 'required',
            'groups'        => 'required',
        ]);

        try {
            $semester->update([
                'title' => $request->title,
                'start_date' => $request->start_date,
                'end_date' => $request->end_date,
            ]);

            $semester->groups()->sync($request->groups);

            if (isset($request->program) && count($request->program) > 0) {
                $semester->programs()->sync($request->program);
            }

            if (isset($request->course) && count($request->course) > 0) {
                $semester->courses()->sync($request->course);
            }

            $message = ['success' => "Semester {$semester->title } created successfully!"];
        } catch (\Exception $e) {
            $message['error'] = $e->getMessage();
        }

        return redirect(route('semesters.index'))
            ->with('success', "Cohort {$semester->title} updated successfully!");
    }

    public function show($id)
    {
        //
    }


    public function destroy(Semester $semester)
    {
        if ($response = $semester->delete()) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $semester->id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    public function addSemester($payload)
    {
        $html = view('back.groups._partials.semester.semester-row')->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    public function programSemester($payload)
    {
        $program = Program::findOrFail($payload['program']);
        $semesters = SemesterHelpers::getSemestersInArrayOnlyTitleId(SemesterHelpers::programHaveSemesters($program));

        if (count($semesters) > 1) {
            $html = view(
                'back.shared._partials.field_value',
                [
                    'data'     => $semesters,
                    'name'     => 'semester',
                    'label'    => 'Semester',
                    'required' => true,
                ]
            )->render();
        } elseif (count($semesters) == 1) {
            $html = view(
                'back.shared._partials.field_value',
                [
                    'data'     => $semesters,
                    'name'     => 'semester',
                    'required' => true,
                    'attr'     => 'disabled',
                    'value'     => key($semesters),
                    'label'    => 'Semester',
                ]
            )->render();
        } else {
            $html = 'No semester assigned to this program';
        }

        return response()->json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }
}
