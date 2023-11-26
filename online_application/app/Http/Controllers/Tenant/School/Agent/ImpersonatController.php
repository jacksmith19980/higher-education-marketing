<?php

namespace App\Http\Controllers\Tenant\School\Agent;

use App\Events\Tenant\Student\StudentCreated;
use App\Helpers\School\AgentHelpers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Tenant\Auth\RegisterController as RegisterController;
use App\Rules\School\StudentExist;
use App\School;
use App\Tenant\Models\Application;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Student;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;
use Session;

class ImpersonatController extends Controller
{
    public function create()
    {
        $applicationList = AgentHelpers::getPublishedApplications(true);

        return view('front.agent.create-student', compact('applicationList'));
    }

    /**
     * @param Request $request
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function bookingCreate(Request $request)
    {
        // TODO validate and move to separate Controller
        $booking = $request->booking;

        $applicationList = AgentHelpers::getApplicationsList();

        return view('front.agent.create-booking-student', compact('applicationList', 'booking'));
    }

    public function bookingStore(Request $request)
    {
        $request->validate([
            'booking' => 'required',
        ]);

        $password = Str::random(8);

        $data = [
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'email'                 => $request->email,
            'password'              => $password,
            'password_confirmation' => $password,
            'role'                  => 'student',
        ];

        $student = app(RegisterController::class)->create($data);

        if (!$student instanceof \App\Tenant\Models\Student) {
            return Response::json([
                'status'    => 400,
                'response'  => 'fail',
                'extra'     => ['errors' => $student->getMessages()],
            ]);
        }

        //School
        $school = School::bySlug($request->school)->first();

        $student->agent()->associate(Auth::guard('agent')->user());

        $student->save();

        $agentStudent = $student;

        $sendNotification = (isset($request->send_email)) ? true : false;

        $data['login_url'] = route('school.login', $school);

        event(new StudentCreated($student, $sendNotification, $data, $this->getContactType()));

        $application_url = null;

        if ($request->booking != 0) {
            $application = Booking::find($request->booking)->quotation->application;
            $application_url = route('application.show', ['school' => $school, 'application' => $application]);

            //Impersonate the student
            $this->impersonate($student);
        }

        //School Applications
        $applications = Application::get();

        $html = view(
            'front.agent._partials.student',
            compact('agentStudent', 'application', 'applications')
        )->render();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'extra'     => [
                'html' => $html,
                'application_id' => $request->application,
                'application_url' => $application_url,
                'booking' => $request->booking,
            ],
        ]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'first_name'            => ['required'],
            'last_name'             => ['required'],
            'email'                 => ['required', 'email', new StudentExist],
            'application'           => ['required'],
        ]);

        $password = Str::random(8);
        $data = [
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'email'                 => $request->email,
            'password'              => $password,
            'password_confirmation' => $password,
            'role'                  => 'student',
        ];

        // Call Create function at Student Register Controller
        // Illuminate\\Support\\MessageBag
        $student = app(RegisterController::class)->create($data);

        //School
        $school = School::bySlug($request->school)->first();

        if ($student instanceof \App\Tenant\Models\Student) {
            $student->agent()->associate(Auth::guard('agent')->user());
            $student->save();
            $agentStudent = $student;

            $sendNotification = (isset($request->send_email)) ? true : false;

            // attache Login URL to the Data before passing to Event
            $data['login_url'] = route('school.login', $school);

            // Add The language
            $data['language'] = $request->language;
            //Dispatch Student Created Event
            event(new StudentCreated($student, $sendNotification, $data, $this->getContactType()));

            $application_url = null;
            if ($request->application != 0) {
                // If Application is selected
                $application = Application::find($request->application);
                $application_url = route('application.show', ['school' => $school, 'application' => $application]);

                //Impersonate the student
                $this->impersonate($student);
            }
            //School Applications
            $applications = Application::student()->get();

            $html = view(
                'front.agent._partials.student',
                compact('agentStudent', 'application', 'applications')
            )->render();

            return Response::json([
                'status'    => 200,
                'response'  => 'success',
                'extra'     => [
                    'html' => $html,
                    'application_id' => $request->application,
                    'application_url' => $application_url,
                ],
            ]);
        } else {
            return Response::json([
                    'status'    => 400,
                    'response'  => 'fail',
                    'extra'     => ['errors' => $student->getMessages()],
                ]);
        }
    }

    /**
     * @TODO We need to get this form School Settings
     */
    protected function getContactType($role = null)
    {
        return 'Applicant';
    }

    public function submitSudentApplication(School $school, Application $application, Student $student)
    {

        // check if student belongs to the agent
        if (Auth::guard('agent')->user()->id != $student->agent_id) {
            return redirect()->back()->withError('Something Wrong');
        }

        // Impersonate the student
        $this->impersonate($student);

        //redirect to the application
        return redirect(route('application.show', ['school' => $school, 'application' => $application]));
    }

    public function impersonate($user)
    {
        // Sign out All Student in the same session
        Auth::guard('student')->logout();

        Session::put('impersonate', $user->id);
        Session::put('impersonated-by', "agent");

        if (Session::has('impersonate')) {
            return true;
        }

        return false;
    }

    public function destroyStudent(Request $request, Student $student)
    {
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

    public function destroy(Request $request)
    {
        Session::forget('impersonate');

        return redirect(route('school.agent.home', $request->school));
    }
}
