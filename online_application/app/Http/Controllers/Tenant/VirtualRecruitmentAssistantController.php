<?php

namespace App\Http\Controllers\Tenant;

use Auth;
use App\School;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Program;
use App\Tenant\Models\Assistant;
use App\Rules\School\StudentExist;
use App\Http\Controllers\Controller;
use App\Tenant\Models\AssistantBuilder;
use Illuminate\Database\QueryException;
use App\Helpers\Assistant\AssistantHelpers;
use App\Helpers\Quotation\QuotationHelpers;
use App\Events\Tenant\Student\StudentRegistred;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use App\Events\Tenant\Assistant\AssistantEmailRequested;
use App\Helpers\Quotation\StudentRegistrationQuotationHelper;

class VirtualRecruitmentAssistantController extends Controller
{
    use AuthenticatesUsers;

    public function show($school, AssistantBuilder $assistantBuilder, Request $request)
    {
        $properties = $assistantBuilder->properties;

        $campuses = null;
        if (isset($properties['show_campus'])) {
            $campuses = $properties['show_campus'] ?
                AssistantHelpers::getAssistantCampus($properties) : false;
        }

        $courses = null;
        if (isset($properties['show_courses'])) {
            $courses = $properties['show_courses'] ?
                AssistantHelpers::getAssistantCourses($properties) : false;
        }

        if (isset($properties['show_programs'])) {
            $programs = $properties['show_programs'] ?
                AssistantHelpers::getAssistantPrograms($properties) : false;
        }

        $financials = null;
        if (isset($properties['show_financial'])) {
            $financials = $properties['show_financial'] ?
                AssistantHelpers::getAssistantFinancials($properties) : false;
        }

        $tabs = $this->getTabs($properties);

        $step = ($request->step) ? $request->step : reset($tabs)['step'];
        $firstStep = reset($tabs)['step'];

        $steps = $this->getPrevNextCurrentStep($step, $tabs);

        $cart = isset(
            $_COOKIE['assistant_cart_'.$assistantBuilder->slug]
        ) ? json_decode($_COOKIE['assistant_cart_'.$assistantBuilder->slug], true) : [];

        $programs = $this->filterProgramsWithSelectedCampus($cart, $programs);

        if ($step == 'apply') {
            $cart = $this->preparateCart($cart);
            if (count($cart) == 0) {
                redirect(route('assistants.show', ['school' => $school, 'assistantBuilder' => $assistantBuilder]));
            }
        }

        return view(
            'front.recruitment_assistant.show',
            compact(
                'assistantBuilder',
                'courses',
                'campuses',
                'programs',
                'financials',
                'firstStep',
                'tabs',
                'steps',
                'step',
                'cart',
                'school'
            )
        );
    }

    public function login($school, AssistantBuilder $assistantBuilder, Request $request)
    {
        $request->validate([
            'email'    => 'required|email',
            'password' => 'required',
        ]);

        $credentials = $request->only('email', 'password');
        if (Auth::guard('student')->attempt($credentials)) {
            $user = Auth::guard('student')->user();
            $assistant = $this->createAssistant($request->cart, $user->id);
        } else {
            return redirect()->back()->withInput($request->only('email'));
        }

        /* if (
            !($assistantBuilder->application->status) ||
            (
                isset($assistantBuilder->application->properties['multiple_submissions']) &&
                $assistantBuilder->application->properties['multiple_submissions'] == 1
            )
        ) {
            return redirect(
                route(
                    'application.show',
                    [
                        'school' => $school,
                        'application' => $assistantBuilder->application,
                        'assistant' => $assistant->id,
                    ]
                )
            );
        } */

        return redirect(
            route(
                'application.show',
                [
                    'school' => $school,
                    'application' => $assistantBuilder->application,
                    'assistant' => $assistant->id,
                ]
            )
        );

        return redirect(
            route(
                'application.index',
                [
                    'school' => $school,
                ]
            )
        );
    }

    public function register(School $school, AssistantBuilder $assistantBuilder, Request $request)
    {
        //$this->logoutUser();

        $request->validate([
            'email'     => ['required', 'string', 'email', new StudentExist()],
            'password'  => 'required|confirmed',
        ]);

        $registrationQuotationHelper = new StudentRegistrationQuotationHelper();

        try {
            if ($user = $registrationQuotationHelper::user($request)) {
                $assistant = $this->createAssistant($request->cart, $user->id);
                Auth::guard('student')->loginUsingId($user->id);

                if (
                    !($assistantBuilder->application->status) ||
                    (
                        isset($assistantBuilder->application->properties['multiple_submissions']) &&
                        $assistantBuilder->application->properties['multiple_submissions'] == 1
                    )
                ) {
                    return redirect(
                        route(
                            'application.show',
                            [
                                'school' => $school,
                                'application' => $assistantBuilder->application,
                                'assistant' => $assistant->id,
                            ]
                        )
                    );
                }

                return redirect(
                    route(
                        'application.index',
                        [
                            'school' => $school,
                        ]
                    )
                );
            }
        } catch (QueryException $e) {
            return back()->withError($e->getMessage())->withInput();
        }

        $user = self::registerUser($request);

        // Dispatch User Registred
        $contactType = isset($assistantBuilder->properties['integrations']['mautic']['contact_type']) ? $assistantBuilder->properties['integrations']['mautic']['contact_type'] : 'Lead';
        event(new StudentRegistred($user, $school, $contactType));

        $assistant = $this->createAssistant($request->cart, $user->id);
        Auth::guard('student')->loginUsingId($user->id);

        return redirect(
            route(
                'application.show',
                [
                    'school' => $school,
                    'application' => $assistantBuilder->application,
                    'assistant' => $assistant->id,
                ]
            )
        );
    }

    /**
     * Send Assistant Email
     *
     * @param string $school
     * @param Assistnat $assistant
     * @param Request $request
     * @return RedirectResponse|Redirector
     */
    public function sendSummaryEmail($school, AssistantBuilder $assistantBuilder, Request $request)
    {
        $request->validate([
        'title'      => 'required',
        'first_name' => 'required',
        'last_name'  => 'required',
        'phone'      => 'required',
        'email'      => 'required|email',
        ]);

        $data = [
        'title'       => $request->title,
        'first_name'  => $request->first_name,
        'last_name'   => $request->last_name,
        'phone'       => $request->phone,
        'email'       => $request->email,
        ];

        // Create Assistant
        $assistant = $this->createAssistant($request->cart);
        $assistant->details = json_encode(['user' => $data]);
        $assistant->save();

        // Dispatch Assistant Email Request event
        $event = event(new AssistantEmailRequested($data, $assistantBuilder, $assistant));

        return redirect(route(
            'recruitment_assistant.thank.you',
            ['school' => $request->school, 'assistantBuilder' => $assistantBuilder, 'assistant' => $assistant]
        ));
    }

    /**
     * Display Assistant Thank you page
     */
    public function assistantThankYouPage($school, AssistantBuilder $assistantBuilder, Assistant $assistant, Request $request)
    {
        return view('front.recruitment_assistant.thank-you', compact('assistantBuilder', 'assistant'));
    }

    protected function getTabs($properties)
    {
        $tabs = [];

        if (isset($properties['show_campus'])) {
            $tabs[] = [
                'step'          => 'campus',
                'step_title'    => isset($properties['campus_step']) ?
                    $properties['campus_step'] : 'Campus',
                'title'         => isset($properties['campus_title']) ?
                    $properties['campus_title'] : 'Campus',
                'sub_title'     => isset($properties['campus_sub_title']) ?
                    $properties['campus_sub_title'] : 'Campus',
                'error_message' => isset(
                    $properties['campus_error_message']
                ) ? $properties['campus_error_message'] : 'Campus its need',
            ];
        }

        if (isset($properties['show_programs'])) {
            $tabs[] = [
                'step'          => 'programs',
                'step_title'    => isset($properties['programs_step']) ?
                    $properties['programs_step'] : 'Program',
                'title'         => isset($properties['programs_title']) ?
                    $properties['programs_title'] : 'Program',
                'sub_title'     => isset($properties['programs_sub_title']) ?
                    $properties['programs_sub_title'] : 'Programs',
                'error_message' => isset(
                    $properties['programs_error_message']
                ) ? $properties['programs_error_message'] : 'Program its need',
            ];
        }

        if (isset($properties['show_courses'])) {
            $tabs[] = [
                'step'          => 'courses',
                'step_title'    => isset($properties['courses_step']) ?
                    $properties['courses_step'] : 'Course',
                'title'         => isset($properties['courses_title']) ?
                    $properties['courses_title'] : 'Course',
                'sub_title'     => isset($properties['courses_sub_title']) ?
                    $properties['courses_sub_title'] : 'Course',
                'error_message' => isset(
                    $properties['courses_error_message']
                ) ? $properties['courses_error_message'] : 'Course its need',
            ];
        }

        if (isset($properties['show_financial'])) {
            $tabs[] = [
                'step'          => 'financial',
                'step_title'    => isset($properties['financial_step']) ?
                    $properties['financial_step'] : 'Financial',
                'title'         => isset($properties['financial_title']) ?
                    $properties['financial_title'] : 'Financial',
                'sub_title'     => isset($properties['financial_sub_title']) ?
                    $properties['financial_sub_title'] : 'Financial',
                'error_message' => isset(
                    $properties['financial_error_message']
                ) ? $properties['financial_error_message'] : 'Financial its need',
            ];
        }
        $tabs[] = [
            'step'          => 'apply',
            'step_title'    => 'Apply',
            'title'         => 'Apply',
            'sub_title'     => null,
            'error_message' => '',
        ];

        return $tabs;
    }

    protected function getPrevNextCurrentStep($current, $tabs)
    {
        $position = 0;
        $currentTab = [];
        foreach ($tabs as $key => $tab) {
            if ($tab['step'] == $current) {
                $position = $key;
                $currentTab = $tab;
            }
        }
        // If a position is found, splice the array.
        if ($position !== false) {
            $next = array_slice($tabs, ($position + 1));
        }

        if ($position !== false) {
            $prev = array_slice($tabs, ($position - 1));
        }

        return [
            'next'    => reset($next),
            'current' => $currentTab,
            'prev'    => reset($prev),
        ];

        return reset($next);
    }

    private function preparateCart(array $cart)
    {
        $new_cart = [];
        if (isset($cart['campuses']) && count($cart['campuses']) > 0) {
            foreach ($cart['campuses'] as $campus) {
                $model = Campus::findOrFail($campus);
                //$new_cart['campuses'][] = Campus::findOrFail($campus);
                $new_cart['campuses'][] = [
                    'id'        => $model->id,
                    'title'     => $model->title,
                    'location'  => isset($model->properties['campus_location']) ? $model->properties['campus_location'] : null,
                ];
            }
        }

        if (isset($cart['programs']) && count($cart['programs']) > 0) {
            foreach ($cart['programs'] as $program) {
                $model = Program::findOrFail(
                    $program['program']
                );

                $new_cart['programs'][] = [

                    /* 'program' => Program::findOrFail(
                        $program['program']
                    ), */

                    'id'        => $model->id,
                    'title'     => $model->title,
                    'details'   => $model->details,

                    'start'   => isset($program['start']) ? $program['start'] : null,

                    'end'   => isset($program['end']) ? $program['end'] : null,

                    'schudel'   => isset($program['schudel']) ? $program['schudel'] : null,
                ];
            }
        }

        if (isset($cart['courses']) && count($cart['courses']) > 0) {
            foreach ($cart['courses'] as $course) {
                $new_cart['courses'][] = [
                    /* 'course' => Course::findOrFail($course['course']), */
                    'course' => $course['course'],
                    'dates'  => $course['dates'],
                ];
            }
        }

        if (isset($cart['financials'])) {
            $new_cart['financials'] = $cart['financials'];
        }

        $new_cart['cart'] = $cart;

        return $new_cart;
    }

    protected function logoutUser(): void
    {
        Auth::guard('student')->logout();
        Session::forget('child-impersonate');
    }

    public static function createAssistant($cart, $user_id = null): Assistant
    {
        if (!is_array($cart)) {
            $cart = json_decode($cart, true);
        }

        if (! $user_id) {
            $user_id = self::getUserId();
            $user_role = 'student';
        }
        return Assistant::create([
            'assistant_builder_id' => request()->assistantBuilder->id,
            'user_id' => $user_id,
            'properties' => $cart,
        ]);
    }

    protected static function getUserId()
    {
        if ($user = Auth::guard('student')->user()) {
            $user_id = $user->id;
        } else {
            $user_id = Str::random(10);
        }

        return $user_id;
    }

    public static function registerUser($request)
    {
        if (! $password = $request->password) {
            $password = Str::random(7);
        }

        $data = [
            'first_name'            => $request->first_name,
            'last_name'             => $request->last_name,
            'email'                 => $request->email,
            'role'                  => 'student',
            'consent'               => $request->consent,
            'password'              => $password,
            'password_confirmation' => $password,
        ];

        return app(\App\Http\Controllers\Tenant\Auth\RegisterController::class)->create($data);
    }

    /**
     * @param array $cart
     * @param $programs
     * @return array
     */
    protected function filterProgramsWithSelectedCampus(array $cart, $programs): array
    {
        if (isset($cart['campuses']) && count($cart['campuses']) > 0) {
            if (isset($programs) && count($programs) > 0) {
                $programs = array_filter($programs, function ($program) use ($cart) {
                    return count(array_intersect($program->campuses->pluck('id')->toArray(), $cart['campuses'])) > 0;
                });
            }
        }

        return $programs;
    }

    public function recuperateMailAssistant($school, Assistant $assistant, $assistant_user_id)
    {
        if ($assistant->user_id != $assistant_user_id) {
            abort(404);
        }

        $assistant_builder = AssistantBuilder::where('id', $assistant->assistant_builder_id)->first();

        $assistant_properties = $assistant->properties;

        $student_information = json_decode($assistant->details, true)['user'];

        $cart = $assistant->properties['cart'];

        return view(
            'front.recruitment_assistant.recuperate.index',
            compact(
                'assistant',
                'assistant_properties',
                'assistant_builder',
                'student_information',
                'cart'
            )
        );
    }
}
