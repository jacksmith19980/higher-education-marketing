<?php

namespace App\Http\Controllers\Tenant;

use App\Events\Tenant\Instructor\InstructorCreated;
use App\Helpers\School\CampusHelpers;
use App\Helpers\School\CourseHelpers;
use App\Helpers\School\GroupHelpers;
use App\Helpers\School\ModelHelpers;
use App\Http\Controllers\Controller;
use App\Repositories\Interfaces\CampusRepositoryInterface;
use App\Repositories\Interfaces\InstructorRepositoryInterface;
use App\Rules\School\InstructorExist;
use App\School;
use App\Tenant\Models\Course;
use App\Tenant\Models\Group;
use App\Tenant\Models\Instructor;
use App\Tenant\Models\Lesson;
use Illuminate\Http\Request;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Hash;
use Response;
use Str;
use Auth;
use Session;

class InstructorController extends Controller
{
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
            'modelName'   => Instructor::getModelName(),
        ];

        $instructors = Instructor::with('campuses')->get();

        return view('back.instructors.index', compact('instructors', 'params'));
    }

    public function create()
    {
        $campuses = CampusHelpers::getCampusesInArrayOnlyTitleId(($this->campus_repository->all())->toArray());
        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();

        return view('back.instructors.create', compact('campuses', 'courses'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name' => 'required|max:100',
            'last_name'  => 'required|max:150',
            'phone'      => ['required' , new InstructorExist('phone')],
            'email'      => ['required', 'email', new InstructorExist('email')],
            'campuses'   => 'required',
        ]);

        $password = Str::random(10);
        $request['password'] = Hash::make($password);

        $instructor = $this->instructor_repository->create(
            $request->only('first_name', 'last_name', 'email', 'password', 'phone')
        );

        $instructor->campuses()->sync($request->campuses);

        if ($request->courses) {
            $instructor->courses()->sync($request->courses);
        }

        $school = School::byUuid(session('tenant'))->firstOrFail();

        $data = $request->only('first_name', 'last_name', 'email', 'password', 'phone');
        $data['password'] = $password;
        $data['login_url'] = route('school.instructor.login', $school);

        event(new InstructorCreated($instructor, true, $data, 'Instructor'));

        return redirect(route('instructors.index'))
            ->with('success', "Instructor {$instructor->name} created successfully!");
    }

    public function show($id)
    {
        $instructor = $this->instructor_repository->findOrFail($id)->load('groups', 'lessons');

        $groups = GroupHelpers::getGroupsInArrayOnlyTitleId();

        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();

        $lessons = Lesson::with('groups', 'course', 'classroomSlot', 'attendances')
            ->where('instructor_id', $instructor->id)
            ->paginate();

        return view('back.instructors.show', compact('instructor', 'lessons'));
    }

    public function edit(Instructor $instructor)
    {
        $campuses = CampusHelpers::getCampusesInArrayOnlyTitleId(($this->campus_repository->all())->toArray());
        $courses = CourseHelpers::getCoursesInArrayOnlyTitleId();

        return view('back.instructors.edit', compact('campuses', 'instructor', 'courses'));
    }

    public function update(Request $request, $id)
    {
        $instructor = $this->instructor_repository->findOrFail($id);

        $request->validate([
            'first_name' => 'required|max:100',
            'last_name'  => 'required|max:150',
            'phone'      => 'required',
            'email'      => ['required', 'max:100', 'email', new InstructorExist('email', $instructor)],
            'campuses'   => 'required',
        ]);

        $input = $request->only(['first_name', 'last_name', 'phone', 'email', 'campuses']);

        $this->instructor_repository->fill($instructor, $input);

        //$this->instructor_repository->campuses($instructor, $request->campuses);
        $instructor->campuses()->sync($request->campuses);

        if ($request->courses) {
            $instructor->courses()->sync($request->courses);
        } else {
            $instructor->courses()->detach(Arr::pluck($instructor->courses, 'id'));
        }

        return redirect(route('instructors.index'))
            ->with('success', "Instructor {$instructor->name} updated successfully!");
    }

    public function destroy($id)
    {
        $instructor = $this->instructor_repository->findOrFail($id);

        if ($response = $this->instructor_repository->delete($instructor)) {
            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => ['removedId' => $id],
            ]);
        } else {
            return Response::json([
                'status'    => 404,
                'response'  => $response,
            ]);
        }
    }

    public function byGroup($payload)
    {
        $group = Group::findOrFail($payload['group']);
        $instructors = ModelHelpers::convertFirstNameLastnameInNameAssocWithId($group->instructors);

        if (count($instructors) > 1) {
            $html = view(
                'back.shared._partials.field_value',
                [
                    'data'     => $instructors,
                    'name'     => 'instructor',
                    'required' => true,
                    'label'    => 'Instructor',
                ]
            )->render();
        } elseif (count($instructors) == 1) {
            $html = view(
                'back.shared._partials.field_value',
                [
                    'data'     => $instructors,
                    'name'     => 'instructor',
                    'required' => true,
                    'attr'     => 'disabled',
                    'value'     => key($instructors),
                    'label'    => 'Instructor',
                ]
            )->render();
        } else {
            $html = 'No instructor assigned to this group';
        }

        return response()->json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    public function byCourse($payload)
    {
        $course = Course::findOrFail($payload['course']);
        $instructors = ModelHelpers::convertFirstNameLastnameInNameAssocWithId($course->instructors);

        if (count($instructors) > 1) {
            $html = view(
                'back.shared._partials.field_value',
                [
                    'data'     => $instructors,
                    'name'     => 'instructor',
                    'required' => true,
                    'label'    => 'Instructor',
                ]
            )->render();
        } elseif (count($instructors) == 1) {
            $html = view(
                'back.shared._partials.field_value',
                [
                    'data'     => $instructors,
                    'name'     => 'instructor',
                    'required' => true,
                    'attr'     => 'disabled',
                    'value'     => key($instructors),
                    'label'    => 'Instructor',
                ]
            )->render();
        } else {
            $html = 'No instructor assigned to this course';
        }

        return response()->json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    public function impersonate(Instructor $instructor , Request $request)
    {
        // Sign out All Instructors in the same session
        Auth::guard('instructor')->logout();
        Session::put('impersonate-instructor', $instructor->id);
        if (Session::has('impersonate-instructor')) {
            $school = School::byUuid(session('tenant'))->firstOrFail();
            return redirect()->route('school.instructor.home', $school);
        }
        return false;
    }

    public function stopImpersonate($instructor)
    {
        Session::forget('impersonate-instructor');
        $school = School::byUuid(session('tenant'))->firstOrFail();
        return redirect()->route('school.instructor.login' , $school);
    }
}
