<?php

namespace App\Http\Controllers\Tenant;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Application;
use App\Tenant\Models\Course;
use App\Tenant\Models\Setting;
use Response;

class CoursesCalendarController extends Controller
{
    public function show(School $school)
    {
        //$courses = Course::with('dates')->where('id' , 133)->get();

        $courses = Course::with('dates')->get();
        $settings = Setting::byGroup();
        $application = Application::findOrFail($settings['calendar']['application']);
        $application_url = $settings['school']['domain'].
            '/'.
            $school->slug.
            '/applications/'.
            $application->slug.'/?course=';
        $start_date_string = '&start_date=';
        $end_date_string = '&end_date=';
        $start_time_string = '&start_time=';
        $end_time_string = '&end_time=';

        $courses_dates = [];

        foreach ($courses as $course) {

            foreach ($course->dates()->get() as $date) {
                $times = $this->getStartEndTime($date);
                switch ($date->date_type) {
                    case 'single-day':
                        $courses_dates[] = [
                            'title' => $course->title,
                            'extendedProps' => [
                                'status'     => ($date->completed) ? 'completed' : 'available',
                                'url_read_more' => isset($course->properties['detail_link']) ?
                                    $course->properties['detail_link'] : '/',
                                'url_register' => $application_url.
                                    $course->id.
                                    $start_date_string.$date->properties['date'].
                                    $end_date_string.$date->properties['date'].
                                    $start_time_string.$date->properties['start_time'].
                                    $end_time_string.$date->properties['end_time'],
                            ],
                            'start' => $date->properties['date'].'T'.$date->properties['start_time'],
                            'end'   => $date->properties['date'].'T'.$date->properties['end_time'],
                        ];
                        break;
                    case 'specific-dates':
                        $courses_dates[] = [
                            'title' => $course->title,
                            'extendedProps' => [
                                'status'     => ($date->completed) ? 'completed' : 'available',
                                'url_read_more' => isset($course->properties['detail_link']) ?
                                $course->properties['detail_link'] : '/',
                                'url_register' => $application_url.
                                    $course->id.
                                    $start_date_string.$date->properties['start_date'].
                                    $end_date_string.$date->properties['end_date'],
                            ],
                            'start' => $date->properties['start_date'].'T'.$times['start_time'],
                            'end'   => $date->properties['end_date'].'T'.$times['end_time'],
                        ];
                        break;
                    default:
                        throw new \Exception('Unexpected value');
                }
            }
        }


        $courses_dates = json_encode($courses_dates, true);

        return view('front.coursesCalendar.calendar', compact('courses_dates'));
    }

    public function addSchedule($payload)
    {
        $html = view('back.settings.calendar.partials.schedule-row')->render();

        return Response::json(
            [
                'status'   => 200,
                'response' => 'success',
                'extra'    => ['html' => $html],
            ]
        );
    }

    private function getStartEndTime($date)
    {
        $settings = Setting::byGroup();


        if (
            isset($settings['calendar']['schedule_start_time']) &&
            isset($settings['calendar']['schedule_end_time']) &&
            isset($settings['calendar']['schedule_label']) &&
            array_key_exists('scheduled', $date->properties)
        ) {
            $start_time = $settings['calendar']['schedule_start_time'][array_search($date->properties['scheduled'], $settings['calendar']['schedule_label'])];
            $end_time = $settings['calendar']['schedule_end_time'][array_search($date->properties['scheduled'], $settings['calendar']['schedule_label'])];
        }else if (
            isset($settings['calendar']['schedule_start_time']) &&
            isset($settings['calendar']['schedule_end_time']) &&
            isset($settings['calendar']['schedule_label']) &&
            isset($date->properties['date_schudel'])
        ){
            $schudelId = (int) $date->properties['date_schudel'];

            $start_time = $settings['calendar']['schedule_start_time'][$schudelId - 1];

            $end_time = $settings['calendar']['schedule_end_time'][$schudelId - 1];

        } else {
            $start_time = '08:30';
            $end_time = '16:00';
        }

        return compact('start_time', 'end_time');
    }
}
