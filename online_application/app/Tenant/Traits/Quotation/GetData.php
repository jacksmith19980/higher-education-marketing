<?php

namespace App\Tenant\Traits\Quotation;

use App\Tenant\Models\Course;
use App\Tenant\Models\Quotation;
use App\Tenant\Models\Setting;
use Response;

/**
 * get data for quotation
 */
trait GetData
{
    /**
     * Get Start Dates By Program
     */
    public function getDateByProgram(Quotation $quotation, $values)
    {

        // @ToDo to be changed to Program Model

        $programs = Course::find($values);
        $settings = Setting::byGroup('quotation');

        $lists = [];

        $programsList = [];

        foreach ($programs as $program) {
            if ($program->properties['dates_type'] == 'specific-dates') {
                foreach ($program->properties['start_date'] as $key => $startDate) {
                    $endDates = $program->properties['end_date'];

                    $val = $startDate.':'.$endDates[$key];

                    $startDate = date('l jS \of F Y', strtotime($startDate));

                    $endDate = date('l jS \of F Y', strtotime($endDates[$key]));

                    $dates = $startDate.' - '.$endDate;

                    $lists['programs'][$program->id][$val] = $dates.' <small class="text-info">('.$settings['school']['default_currency'].''.$program->properties['date_price'][$key].')</small>';

                    $programsList[$program->id] = $program->title;
                }
            }
        }

        $html = view('front.quotation._partials.date.'.$program->properties['dates_type'], compact('lists', 'quotation', 'programsList'))->render();

        return Response::json([

            'status' => 200,

            'response' => 'success',

            'extra' => ['html' => $html],

        ]);
    }

    public function getAddonsByProgram(Quotation $quotation, $payload)
    {

        // @ToDo to be changed to Program Model

        $program = Course::find($payload['program']);

        $date = $payload['date'];

        $settings = Setting::byGroup('school');

        $addon = [];

        // No Addons
        if (! isset($program->properties['addons']['addon_options_category'])) {
            $html = '';

            return Response::json([

                'status' => 200,

                'response' => 'success',

                'extra' => ['html' => $html],

            ]);
        }

        foreach ($program->properties['addons']['addon_options_category'] as $key => $catrgory) {
            if (in_array($catrgory, $quotation->properties['addons'])) {
                $addon[$key] =

                $program->properties['addons']['addon_options'][$key].' - '.$settings['school']['default_currency'].''.$program->properties['addons']['addon_options_price'][$key];
            }
        }
        $addon[99999] = 'I don\'t want to book extra activities';

        $html = view('front.quotation._partials.addon.index', compact('addon', 'quotation', 'program', 'date'))->render();

        return Response::json([

            'status' => 200,

            'response' => 'success',

            'extra' => ['html' => $html],

        ]);
    }

    /**
     * Get Programs By Campus and Quotation
     */
    public function getProgramByCampus(Quotation $quotation, $values)
    {

        // Quotation Programs

        $quotationCourses = $this->getQuotationCourses($quotation)->with('campuses')->get();

        $programs = [];

        foreach ($quotationCourses as $course) {
            $courseCampuses = $course->campuses()->get()->pluck('id')->toArray();

            if (count(array_intersect($courseCampuses, $values))) {
                $programs[$course->id] = $course->title;
            }
        }

        $html = view('front.quotation._partials.program.index', compact('programs', 'quotation'))->render();

        return Response::json([

            'status' => 200,

            'response' => 'success',

            'extra' => ['html' => $html],

        ]);
    }

    /**
     * Get Courses By Dates
     */
    public function getCoursesByDates(Quotation $quotation, $values)
    {

        //Courses

        $quotationCourses = $ths->getQuotationCourses($quotation)->get();

        foreach ($quotationCourses as $quotationCourse) {
            foreach ($quotationCourse->properties['start_date'] as $key => $startDate) {
                $date = $startDate.':'.$quotationCourse->properties['end_date'][$key];

                if (in_array($date, $values)) {
                    $courses[] = $quotationCourse->id;
                }
            }
        }

        $courses = array_unique($courses);

        $courses = Course::whereIn('id', $courses)->pluck('title', 'id')->toArray();

        $html = view('front.quotation._partials.course.course', compact('courses', 'quotation'))->render();

        return Response::json([

            'status' => 200,

            'response' => 'success',

            'extra' => ['html' => $html],

        ]);
    }

    /**
     * Get the Quotation courses
     */
    protected function getQuotationCourses(Quotation $quotation)
    {

        // @ToDo to be changed to Program Model

        return Course::whereIn('id', $quotation->properties['courses']);
    }
}
