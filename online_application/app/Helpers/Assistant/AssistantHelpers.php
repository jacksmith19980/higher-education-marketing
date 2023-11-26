<?php

namespace App\Helpers\Assistant;

use App\Helpers\Quotation\QuotationHelpers;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Program;
use App\Tenant\Models\Schedule;

class AssistantHelpers
{
    public static function getAssistantCampus($properties)
    {
        $campuses = [];
        foreach ($properties['campuses'] as $campus_id) {
            $campuses[] = Campus::findOrFail($campus_id);
        }

        return $campuses;
    }

    public static function getAssistantCourses($properties)
    {
        $courses = [];
        foreach ($properties['courses'] as $course_id) {
            $courses[] = Course::findOrFail($course_id);
        }

        return $courses;
    }

    public static function getAssistantPrograms($properties)
    {
        $programs = [];
        foreach ($properties['programs'] as $program_id) {
            $programs[] = Program::findOrFail($program_id);
        }

        return $programs;
    }

    public static function getAssistantFinancials($properties)
    {
        return $properties['financial'];
    }

    public static function formatStartEndDates($dates, $separator = ':')
    {
        $tmp = explode($separator, $dates);

        foreach ($tmp as $key => $date) {
            $tmp[$key] = date('l jS \of F Y', strtotime($date));
        }

        return implode(' to ', $tmp);
    }

    public static function getAssistantDetails($details)
    {
        $html = '';
        foreach ($details['programs'] as $program) {
            $html .= '<h4><strong>'.$program['title'].'</strong></h4>';
            $html .= '<span style="display:block;maring:10px 0;">'.QuotationHelpers::formateStartEndDates(
                    $program['start'].':'.$program['end']
                ).'</span>';
        }

        return $html;
    }

    public static function getSchedule($schedule_id)
    {
        $sch = Schedule::where('id', $schedule_id)->first();
        $schedule = $sch['start_time'].' - '.$sch['end_time'];

        return $schedule;
    }
}
