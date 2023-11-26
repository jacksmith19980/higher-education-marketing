<?php

namespace App\Http\Controllers\Tenant;

use Auth;
use Response;
use App\Tenant\Models\Cart;
use App\Tenant\Models\Date;
use App\Tenant\Models\Addon;
use App\Tenant\Models\Field;
use Illuminate\Http\Request;
use App\Tenant\Models\Program;
use App\Tenant\Models\Schedule;
use App\Helpers\cart\CartHelpers;
use App\Tenant\Models\Application;
use App\Http\Controllers\Controller;
use App\Repository\CourseRepository;

class CartController extends Controller
{
    public function show(Request $request)
    {
        $application_id = $request->application;
        $field_id = $request->element;

        $application = Application::findOrFail($application_id);
        $field = Field::findOrFail($field_id);

        $user = \Illuminate\Support\Facades\Auth::guard('student')->user();
        $submission = $user->submissions()->where('application_id', $application->id)->first();

        $cart = CartHelpers::getCart($application);

        $program = $cart['programs'];

        $label = 'programs';

        $html = view(
            'front.applications.application-layouts.oiart.cartReview.partials.price-container',
            compact('label', 'field', 'cart', 'application', 'submission')
        )->render();

        return Response::json([
              'status'   => 200,
              'response' => 'success',
              'extra'    => [
                  'html' => $html,
              ],
          ]);
    }

    public function courseDate(Request $request)
    {
        $course_slug = $request->course;
        $date_id = $request->date;
        $application_id = $request->application;
        $submission = $request->submission;
        $hash = $request->hash;

        try {
            $student = Auth::guard('student')->user();
            $application = Application::findOrFail($application_id);

            $cart = $this->getCart($student, $application);

            $date = Date::findOrFail($date_id);

            $data = $cart->data;
            $data['courses'] = $this->generateCourseCartData($cart, $course_slug, $date, $hash)['courses'];
            $cart->data = $data;

            $cart->application()->associate($application);
            $cart->student()->associate($student);
            $cart->submission()->associate($submission);

            $cart->save();

            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'message'  => 'ok',
                ]
            );
        } catch (\Exception $exception) {
            return Response::json(
                [
                    'status'   => 500,
                    'response' => 'error',
                    'message'  => $exception->getMessage(),
                ]
            );
        }
    }

    public function deleteCourse(Request $request)
    {
        $application_id = $request->application;
        $hash = $request->hash;
        $student = Auth::guard('student')->user();
        $application = Application::findOrFail($application_id);
        $cart = $this->getCart($student, $application);

        try {
            if ($this->existCourseOnCartByHash($cart->data, $hash)) {
                $data = [];
                foreach ($cart->data['courses'] as $cart_course) {
                    if ($cart_course['hash'] != $hash) {
                        $data['courses'][] = $cart_course;
                    }
                }
                $cart->data = $data;
            }
            $cart->save();

            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'message'  => 'ok',
                ]
            );
        } catch (\Exception $exception) {
            return Response::json(
                [
                    'status'   => 500,
                    'response' => 'error',
                    'message'  => $exception->getMessage(),
                ]
            );
        }
    }

    public function addonsCourse(Request $request)
    {
        $application_id = $request->application;
        $course_slug = $request->course;
        $action_add = $request->action === 'true' ? true : false;
        $hash = $request->hash;
        $addon_key = $request->addon;

        $student = Auth::guard('student')->user();
        $application = Application::findOrFail($application_id);
        $cart = $this->getCart($student, $application);

        try {
            if ($this->existCourseOnCartByHash($cart->data, $hash)) {
                $data = $cart->data;
                unset($data['courses']);
                foreach ($cart->data['courses'] as $cart_course) {
                    if ($this->cartCourseHaveHashAndSlug($cart_course, $hash, $course_slug)) {
                        if ($action_add) {
                            $addon = Addon::where('key', $addon_key)->first();

                            $cart_course['addons'][] = [
                                'key'   => $addon_key,
                                'price' => $addon->price,
                            ];
                        } elseif ($this->existAddonInCourseOnCart($cart_course, $addon_key)) {
                            $addons = [];
                            foreach ($cart_course['addons'] as $addon) {
                                if ($addon['key'] != $addon_key) {
                                    $addons[] = $addon;
                                }
                            }
                            $cart_course['addons'] = $addons;
                        }
                    }
                    $data['courses'][] = $cart_course;
                    $cart->data = $data;
                }
                $cart->save();

                return Response::json(
                    [
                        'status'   => 200,
                        'response' => 'success',
                        'message'  => 'ok',
                    ]
                );
            }

            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'error',
                    'message'  => 'Need add course first',
                ]
            );
        } catch (\Exception $exception) {
            return Response::json(
                [
                    'status'   => 500,
                    'response' => 'error',
                    'message'  => $exception->getMessage(),
                ]
            );
        }
    }

    public function addonsDateCourse(Request $request)
    {
        $application_id = $request->application;
        $course_slug = $request->course;
        $action_add = $request->action === 'true' ? true : false;
        $hash = $request->hash;
        $addon_key = $request->addon;
        $date_id = $request->addonDate;

        $student = Auth::guard('student')->user();
        $application = Application::findOrFail($application_id);
        $cart = $this->getCart($student, $application);
        $date = Date::findOrFail($date_id);

        try {
            if ($this->existCourseOnCartByHash($cart->data, $hash)) {
                $cart_courses = array_map(
                    function ($cart_course) use ($date, $hash, $course_slug, $addon_key, $action_add) {
                        if ($action_add) {
                            if ($this->cartCourseHaveHashAndSlug($cart_course, $hash, $course_slug)) {
                                $cart_course['date']['addons'][] = [
                                    'key'        => $addon_key,
                                    'price'      => $date['properties']['addons'][$addon_key]['price'],
                                    'price_type' => $date['properties']['addons'][$addon_key]['price_type'],
                                ];
                            }
                        } else {
                            $AddonsDate = array_filter(
                                $cart_course['date']['addons'],
                                function ($addon) use ($addon_key) {
                                    return $addon['key'] == $addon_key ? false : true;
                                }
                            );
                            $cart_course['date']['addons'] = $AddonsDate;
                        }

                        return $cart_course;
                    },
                    $cart->data['courses']
                );

                $data['courses'] = $cart_courses;
                $cart->data = $data;
                $cart->save();

                return Response::json(
                    [
                        'status'   => 200,
                        'response' => 'success',
                        'message'  => 'ok',
                    ]
                );
            }

            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'message'  => 'Need add course first',
                ]
            );
        } catch (\Exception $exception) {
            return Response::json(
                [
                    'status'   => 500,
                    'response' => 'error',
                    'message'  => $exception->getMessage(),
                ]
            );
        }
    }

    public function programDate(Request $request)
    {
        $program_slug = $request->program;
        $registration_fees = $request->registration_fees;
        $application_id = $request->application;
        $submission = ($request->has("submission")) ? $request->submission : null;
        $start_date = $request->start_date;

        $program = Program::where('slug', $program_slug)->first();

        $student = Auth::guard('student')->user();
        $application = Application::findOrFail($application_id);

        $cart = $this->getCart($student, $application);
        $data = $cart->data;

        $data['programs'] = $this->handleProgramData($program, $program_slug, $start_date, $cart, $registration_fees);

        $cart->data = $data;

        $cart->application()->associate($application);
        $cart->student()->associate($student);
        if($submission){
            $cart->submission()->associate($submission);
        }
        $cart->save();
        return Response::json(
            [
                'status'    => 200,
                'response'  => 'success',
                'message'   => 'ok',
                'cart'      => $cart->toArray()
            ]
        );
    }

    public function addonsProgram(Request $request)
    {
        $application_id = $request->application;
        $program_slug = $request->program;
        $action_add = $request->action === 'true' ? true : false;
        $addon_title = $request->addon;

        $program = Program::where('slug', $program_slug)->first();

        $student = Auth::guard('student')->user();
        $application = Application::findOrFail($application_id);
        $cart = $this->getCart($student, $application);

        try {
            if ($this->existProgramOnCartBySlug($cart->data, $program_slug)) {
                $program_cart_array = $this->getFromCart($cart->data, 'programs');
                $program_addons = $program->properties['addons'];
                $cart_program = $program_cart_array['element'];
                if ($action_add) {
                    $cart_program['addons'][] = [
                        'addon_options_category' => $program_addons['addon_options_category'][array_search(
                            $addon_title,
                            $program_addons['addon_options']
                        )],
                        'addon_options'          => $addon_title,
                        'addon_options_price'    => $program_addons['addon_options_price'][array_search(
                            $addon_title,
                            $program_addons['addon_options']
                        )],
                    ];
                } else {
                    $addons = array_filter(
                        $cart_program['addons'],
                        function ($addon) use ($addon_title) {
                            return $addon['addon_options'] === $addon_title ? false : true;
                        }
                    );
                    $cart_program['addons'] = $addons;
                }

                $cart_data = $program_cart_array['data'];
                $cart_data['programs'] = $cart_program;

                $cart->data = $cart_data;
                $cart->save();

                return Response::json(
                    [
                        'status'   => 200,
                        'response' => 'success',
                        'message'  => 'ok',
                    ]
                );
            }

            return Response::json(
                [
                    'status'   => 200,
                    'response' => 'success',
                    'message'  => 'Need select program date first',
                ]
            );
        } catch (\Exception $exception) {
            return Response::json(
                [
                    'status'   => 500,
                    'response' => 'error',
                    'message'  => $exception->getMessage(),
                ]
            );
        }


    }

    public function addons(Request $request)
    {
        $label = $request->label;
        $price = $request->price;
        $application_id = $request->application;
        $action_add = $request->action === 'true' ? true : false;

        try {
            $student = Auth::guard('student')->user();
            $application = Application::findOrFail($application_id);

            $cart = $this->getCart($student, $application);
            $cart_data = $cart->data;

            if ($action_add) {
                if (! $this->existAddonOnCart($cart_data, $label)) {
                    $cart_data['addons'][] = [
                        'title' => $label,
                        'price' => $price,
                    ];
                }
            } else {
                $cart_addons = $cart_data['addons'];
                $addons = array_filter(
                    $cart_addons,
                    function ($addon) use ($label) {
                        return $addon['title'] === $label ? false : true;
                    }
                );
                $cart_data['addons'] = $addons;
            }
            $cart->data = $cart_data;
            $cart->application()->associate($application);
            $cart->student()->associate($student);
            $cart->save();

            return Response::json([
                'status'   => 200,
                'response' => 'success',
                'message'  => 'ok',
            ]);
        } catch (\Exception $exception) {
            return Response::json([
                'status'   => 500,
                'response' => 'error',
                'message'  => $exception->getMessage(),
            ]);
        }
    }

    /**
     * Return element from cart(course, program, addon) and cart
     * element its deleted from cart allowing modification and re insert latter
     * @return array
     */
    private function getFromCart($cart, $element): array
    {
        $return = $cart[$element];
        unset($cart[$element]);

        return ['element' => $return, 'data' => $cart];
    }

    private function existAddonInCourseOnCart($cart_course, $addon_key): bool
    {
        if (empty($cart_course['addons'])) {
            return false;
        }

        return array_search($addon_key, (array) array_column($cart_course['addons'], 'key')) !== false ? true : false;
    }

    private function newCartCourse($course_slug, $date, $hash)
    {
        $date_data = $this->handleDateData($date);
        $course = CourseRepository::bySlug($course_slug);

        return [
            'slug'  => $course->slug,
            'title' => $course->title,
            'date'  => $date_data,
            'price' => $date->properties['date_price'],
            'hash'  => $hash === null ? 0 : $hash,
        ];
    }

    private function existCourseOnCart($cart_data, $course_slug): bool
    {
        if (empty($cart_data) || ! array_key_exists('courses', $cart_data)) {
            return false;
        }

        return array_search($course_slug, (array) array_column($cart_data['courses'], 'slug')) !== false ? true : false;
    }

    private function existCourseOnCartByHash($cart_data, $hash): bool
    {
        if (empty($cart_data)) {
            return false;
        }

        return array_search($hash, (array) array_column($cart_data['courses'], 'hash')) !== false ? true : false;
    }

    private function existAddonOnCart($cart_data, $title): bool
    {
        if (empty($cart_data) || ! array_key_exists('addons', $cart_data)) {
            return false;
        }

        return array_search($title, (array) array_column($cart_data['addons'], 'title')) !== false ? true : false;
    }

    private function existProgramOnCartBySlug($cart_data, $slug): bool
    {
        if (empty($cart_data)) {
            return false;
        }

        return $cart_data['programs']['slug'] == $slug ? true : false;
    }

    protected function getCart($student, $application)
    {
        $carts = Cart::ByStudentAndApplication($student, $application)->get();

        if ($carts->count() > 1) {
            throw new \Exception('To many Carts');
        }

        if ($carts->count() === 1) {
            $cart = $carts->first();
        } else {
            $cart = new Cart();
        }

        return $cart;
    }

    protected function handleDateData($date): array
    {
        switch ($date->date_type) {
            case 'specific-dates':
                $date_data = [
                    'id'    => $date->id,
                    'start' => $date->properties['start_date'],
                ];
                break;
            case 'all-year':
                $date_data = [
                    'id'    => $date->id,
                    'start' => $date->properties['start_date'],
                ];
                break;
            case 'single-day':
                $date_data = [
                    'id'    => $date->id,
                    'start' => $date->properties['date'],
                ];
                break;
            default:
                throw new \Exception('Unknown date type '.$date->date_type);
        }

        return $date_data;
    }

    protected function generateCourseCartData(Cart $cart, $course_slug, $date, $hash)
    {
        if ($this->existCourseOnCart($cart->data, $course_slug)) {
            foreach ($cart->data['courses'] as $cart_course) {
                // if course slug its on cart
                if ($cart_course['slug'] == $course_slug) {
                    // replace course cart for new
                    $data['courses'][] = $this->newCartCourse($course_slug, $date, $hash);
                } else {
                    $data['courses'][] = $cart_course;
                }
            }
        } else {
            if (! empty($cart->data) && array_key_exists('courses', $cart->data)) {
                foreach ($cart->data['courses'] as $cart_course) {
                    $data['courses'][] = $cart_course;
                }
            }

            $data['courses'][] = $this->newCartCourse($course_slug, $date, $hash);
        }

        return $data;
    }

    protected function cartCourseHaveHashAndSlug($cart_course, $hash, $course_slug): bool
    {
        return $cart_course['hash'] == $hash && $cart_course['slug'] == $course_slug;
    }

    protected function handleProgramData($program, $program_slug, $start_date, $cart, $registration_fees): array
    {
        $addons = [];
        $result = [];
        if ($cart->data != '' && array_key_exists('programs', $cart->data)) {
            if (array_key_exists('addons', $cart->data['programs'])) {
                $addons = $cart->data['programs']['addons'];
            }
        }

        $index = array_search($start_date, $program->properties['start_date']);

        $program_cart = [
            'slug'              => $program_slug,
            'title'             => $program->title,
            'registration_fees' => $registration_fees,
            'price_type'        => $program->properties['dates_type'],
            'regular_price'     => isset($program->properties['date_price'][$index]) ?
            $program->properties['date_price'][$index] : null,
        ];

        $result = array_merge(
            $program_cart,
            $this->handleProgramDate($program->properties, $start_date)
        );

//        switch ($program->properties['date_price'][price_type]) {
//            case 'flat-rate':
//                $program_cart = [
//                    "slug"          => $program_slug,
//                    "title"             => $program->title,
//                    'registration_fees' => $registration_fees,
//                    "price_type"    => $program->properties['price_type'],
//                    "regular_price" => $program->properties['pricing']['regular_price']
//                ];
//                $result = array_merge(
//                    $program_cart,
//                    $this->handleProgramDate($program->properties, $start_date)
//                );
//                break;
//            case 'flat-rate-week':
//                $program_cart = [
//                    "slug"                   => $program_slug,
//                    "title"                  => $program->title,
//                    "price_type"             => $program->properties['price_type'],
//                    "number_of_weeks"        => $program->properties['pricing']['number_of_weeks'],
//                    "regular_price_per_week" => $program->properties['pricing']['regular_price_per_week'],
//                    'registration_fees' => $registration_fees,
//                ];
//                $result = array_merge(
//                    $program_cart,
//                    $this->handleProgramDate($program->properties, $start_date)
//                );
//                break;
//            case 'n-a':
//                throw new \Exception('No price type');
//            default:
//                throw new \Exception('No price type');
//        }
        $result['addons'] = $addons;
        return $result;
    }

    protected function handleProgramDate($progra_properties, $start_date)
    {
        $start_date = explode("_" , $start_date)[0];
        if( $scheduleId = $progra_properties['date_schudel'][array_search(
        $start_date,
        $progra_properties['start_date']
        )]){

            $schedule = Schedule::find($scheduleId);
        }
        switch ($progra_properties['dates_type']) {
            case 'specific-dates':
                return [
                    'dates_type'   => $progra_properties['dates_type'],
                    'start_date'   => $start_date,
                    'end_date'     => $progra_properties['end_date'][array_search(
                        $start_date,
                        $progra_properties['start_date']
                    )],
                    'date_schudel' => isset($schedule) ? $schedule->id : null,
                    'date_schudel_label' => isset($schedule) ? $schedule->label : null,
                ];
            case 'specific-intakes':

                return [
                    'dates_type' => $progra_properties['dates_type'],
                    'start_date' => $start_date,
                    'date_schudel' => isset($schedule) ? $schedule->id : null,
                    'date_schudel_label' => isset($schedule) ? $schedule->label : null,
                ];
        }
    }
}
