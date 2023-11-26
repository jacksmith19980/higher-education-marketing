<?php

namespace App\Http\Controllers\Tenant\School;

use App\Events\Tenant\Student\ChildAccountCreated;
use App\Events\Tenant\Student\StudentRegistred;
use App\Helpers\Application\SubmissionHelpers;
use App\Helpers\School\AgentHelpers;
use App\Http\Controllers\Controller;
use App\Http\Controllers\Tenant\Auth\RegisterController;
use App\School;
use App\Tenant\Models\Application;
use App\Tenant\Models\Setting;
use App\Tenant\Models\Student;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Response;
use Session;

class ChildImpersonatController extends Controller
{
    public function create(Request $request)
    {
        $booking = $request->booking;

        if (Auth::guard('student')->user()->role == 'parent') {
            $parent_id = Auth::guard('student')->user()->id;
        } else {
            $parent_id = Auth::guard('student')->user()->parent_id;
        }

        $children = Student::where('parent_id', $parent_id)->get();
        $childrenList = SubmissionHelpers::getChildrenList($children);

        $application = (isset($request->application)) ? $request->application : null;
        $applicationList = AgentHelpers::getApplicationsList($application);

        return view('front.parent._partials.child.create-child', compact('applicationList', 'booking', 'childrenList'));
    }

    public function store(Request $request)
    {
        Session::forget('child-impersonate');

        $newChild = false;
        $settings = Setting::byGroup();
        //School
        $school = School::bySlug($request->school)->first();
        $school_domain = $this->getSchoolDomain($settings['school']['website']);

        // Get Parent ID
        if (Auth::guard('student')->user()->role == 'parent') {
            $parentId = Auth::guard('student')->user()->id;
        } else {
            $parentId = Auth::guard('student')->user()->parent_id;
        }
        //$parentId = Auth::guard('student')->user()->id;

        // If the child is already in the system
        if ($request->has('child') && $request->child != 0) {
            $child = Student::where(
                [
                    'id'          => $request->child,
                    'parent_id'   => $parentId,
                ]
            )->firstOrFail();
        } else {
            $newChild = true;
            $child = $this->createNewChild($request, $parentId, $school_domain);
        }

        if ($child instanceof \App\Tenant\Models\Student) {
            //Run New Child Account Events
            if ($newChild && $request->booking != null) {
                // Dispatch Student Created Event
                event(new ChildAccountCreated($child, $school, $this->getContactType(), $request->booking));
            }
            $application_url = null;
            if ($request->application != 0) {
                // If Application is selected
                $application = Application::find($request->application);
                $application_url = route('application.show', ['school' => $school, 'application' => $application]);

                //Impersonate the student
                $this->impersonate($child);
            }

            //School Applications
            $applications = Application::student()->get();
            $bookings = [];
            $html = view(
                'front.parent._partials.child.child',
                compact('child', 'application', 'applications', 'bookings')
            )->render();

            return Response::json([
                'status' => 200,
                'response' => 'success',
                'extra' => [
                    'html' => $html,
                    'application_id' => $request->application,
                    'application_url' => $application_url,
                    'booking' => $request->booking,
                    'child' => $child->id,
                ],
            ]);
        } else {
            return Response::json([
                'status' => 400,
                'response' => 'fail',
                'extra' => ['errors' => $child->getMessages()],
            ]);
        }
    }

    /**
     * Create New Child Account
     *
     * @param Request $request
     * @param [type] $parentId
     * @param [type] $school_domain
     * @return void
     */
    protected function createChildEmail(Request $request, $parentId, $school_domain)
    {
        $email = str_replace(
            ' ',
            '',
            $request->first_name
        ).'_'.
            str_replace(' ', '', $request->last_name).'_'.$parentId.'@'.$school_domain;

        $email = strtolower(str_replace(' ', '', $email));
        $email = filter_var($email, FILTER_SANITIZE_EMAIL);

        return $email;
    }

    protected function createNewChild(Request $request, $parentId, $school_domain)
    {

        // Create Random Passowrd
        $password = Str::random(8);

        // Create Random Email
        $email = $this->createChildEmail($request, $parentId, $school_domain);

        $data = [
            'first_name' => $request->first_name,
            'last_name' => $request->last_name,
            'email' => $email,
            'password' => $password,
            'role' => 'student',
            'parent_id' => $parentId,
            'password_confirmation' => $password,
        ];

        // Call Create function at Student Register Controller
        // Illuminate\\Support\\MessageBag
        return  app(RegisterController::class)->create($data);
    }

    /**
     * @TODO We need to get this form School Settings
     */
    protected function getContactType($role = null)
    {
        return 'Student';
    }

    public function submitChildApplication(School $school, Application $application, Student $student)
    {
        // check if Child belongs to the parent
        if (Auth::guard('student')->user()->id != $student->parent_id) {
            return redirect()->back()->withError('Something Wrong');
        }

        // Impersonate the student
        $this->impersonate($student);
        $args = ['school' => $school, 'application' => $application];
        if ($booking = request('booking')) {
            $args['booking'] = $booking;
        }
        //redirect to the application
        return redirect(route('application.show', $args));
    }

    /**
     * Impersonate Child
     */
    public function impersonate($user)
    {
        // Sign out All Student in the same session
        //Auth::guard('student')->logout();

        if ($user->role == 'student') {
            Session::put('child-impersonate', $user->id);

            return true;
        } else {
            Session::forget('child-impersonate');

            return false;
        }

        /* if (Session::has('child-impersonate')) {
            return true;
        } */
        //return false;
    }

    /**
     * Stop Impersonating the Child
     */
    public function destroy(Request $request)
    {
        Session::forget('child-impersonate');

        return redirect(route('school.parents', $request->school));
    }

    protected function getSchoolDomain($website)
    {
        $tmp = explode('.', $website);

        return $tmp[1].'.'.$tmp[2];
    }
}
