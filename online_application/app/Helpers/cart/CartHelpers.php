<?php

namespace App\Helpers\cart;

use App\Tenant\Models\Cart;
use App\Tenant\Models\Program;
use Auth;

class CartHelpers
{
    public static function getCart($application)
    {
        $result = ['programs' => [], 'courses' => [], 'addons' => []];

        $student = Auth::guard('student')->user();

        $cart = Cart::where(['application_id' => $application->id, 'student_id' => $student->id])->first();

        if (! $cart) {
            return $result;
        }

        if (array_key_exists('programs', $cart->data)) {
            $result['programs'] = $cart->data['programs'];
        }
        if (array_key_exists('courses', $cart->data)) {
            $result['courses'] = $cart->data['courses'];
        }

        if (array_key_exists('addons', $cart->data)) {
            $result['addons'] = $cart->data['addons'];
        }

        return $result;
    }

    public static function getCartTotalPrice($cart)
    {

        // @Enrique Add Program's Registeration Fee

        $total_programs = 0;
        $total_courses = 0;
        $total_addons = 0;
        $programs_fees = 0;
        $courses_fees = 0;

        if (array_key_exists('programs', $cart) && count($cart['programs']) > 0) {
            $price_program = $cart['programs']['regular_price'];
            $program_reg_fees = $cart['programs']['registration_fees'];

            $program = Program::where('slug', $cart['programs']['slug'])->first();
            $programs_fees = $program->properties['program_registeration_fee'] ? $program->properties['program_registeration_fee'] : 0;

            $price_addons = 0;
            if (array_key_exists('addons', $cart['programs'])) {
                foreach ($cart['programs']['addons'] as $addons) {
                    $price_addons += $addons['addon_options_price'];
                }
            }
            $total_programs = $price_program + $price_addons + $program_reg_fees;
        }

        if (array_key_exists('addons', $cart)) {
            foreach ($cart['addons'] as $addon) {
                $total_addons += $addon['price'];
            }
        }

        if (array_key_exists('courses', $cart)) {
            $total_courses = 0;
            $price_courses_date_addons = 0;
            $price_courses_addons = 0;
            foreach ($cart['courses'] as $course) {
                $total_courses += $course['price'];
                if (array_key_exists('addons', $course['date'])) {
                    foreach ($cart['courses']['date']['addons'] as $addons) {
                        $price_courses_date_addons += $addons['price'];
                    }
                }
                if (array_key_exists('addons', $course)) {
                    foreach ($course['addons'] as $addons) {
                        $price_courses_addons += $addons['price'];
                    }
                }
            }

            $total_courses = $total_courses + $price_courses_date_addons + $price_courses_addons;
        }

        return [
            'total' => $total_programs + $total_courses + $total_addons,
            'programs' => $total_programs,
            'programs_fees' => $programs_fees,
            'courses' => $total_courses,
            'courses_fees' => $courses_fees,
            'addons' => $total_addons,
        ];
    }

    public static function getFirstStartdate($application)
    {
        $student = Auth::guard('student')->user();

        $cart = Cart::where(['application_id' => $application->id, 'student_id' => $student->id])->first();

        $date = null;
        $program_date = null;
        $course_date = null;
        if (array_key_exists('programs', $cart->data)) {
            $program_date = $cart->data['programs']['start_date'];
        }

        if (array_key_exists('courses', $cart->data)) {
            foreach ($cart->data['courses'] as $course) {
                if ($course_date && $course_date > $course['date']['start']) {
                    $course_date = $course['date']['start'];
                }
            }
        }

        if ($course_date && $program_date) {
            if ($program_date < $course_date) {
                $date = $program_date;
            } else {
                $date = $course_date;
            }
        } elseif ($course_date) {
            $date = $course_date;
        } else {
            $date = $program_date;
        }

        return $date;
    }

    /**
     * Total adddons amount
     * program addons, course addons and global addons amount
     */
    public static function getAddonsAmount($application)
    {
        $courses_addons_price = 0;
        $programs_addons_price = 0;
        $addons_price = 0;
        $cart = self::getCart($application);
        if (! empty($cart['courses'])) {
            foreach ($cart['courses'] as $course) {
                if (array_key_exists('addons', $course)) {
                    $courses_addons_price += self::addonsPrice($course['addons']);
                }

                if (array_key_exists('addons', $course['date'])) {
                    $courses_addons_price += self::addonsPrice($course['date']['addons']);
                }
            }
        }

        if (! empty($cart['programs'])) {
            if (array_key_exists('addons', $cart['programs'])) {
                $programs_addons_price += self::addonsPrice($cart['programs']['addons']);
            }
        }

        if (! empty($cart['addons'])) {
            $addons_price += self::addonsPrice($cart['addons']);
        }

        return $courses_addons_price + $programs_addons_price + $addons_price;
    }

    protected static function addonsPrice(array $addons): int
    {
        $total = 0;
        foreach ($addons as $addon) {
            $total += $addon['price'];
        }

        return $total;
    }
}
