<?php

namespace App\Helpers\Application;

use App;
use Arr;
use Auth;
use Carbon\Carbon;
use App\Tenant\Models\Date;
use App\Tenant\Models\Addon;
use App\Tenant\Models\Field;
use App\Tenant\Models\Campus;
use App\Tenant\Models\Course;
use App\Tenant\Models\Booking;
use App\Tenant\Models\Program;
use App\Tenant\Models\Assistant;
use App\Tenant\Models\Submission;
use App\Tenant\Models\Application;
use App\Tenant\Models\CustomField;
use App\Tenant\Models\SubmissionStatus;
use Illuminate\Support\Facades\Session;
use App\Helpers\Quotation\QuotationHelpers;
use App\Events\Tenant\Submission\SubmissionStatusChanged;

/**
 * SubmissionHelpers
 */
class SubmissionHelpers
{
    /**
     * Get select list of childeren
     */
    public static function getChildrenList($children)
    {
        $list = [];
        foreach ($children as $child) {
            $list[$child->id] = $child->first_name . ' ' . $child->last_name;
        }
        $list[99999] = '-- Add new child --';

        return $list;
    }

    /**
     * Get Submission Status
     *
     * @param [type] $status
     * @return void
     */
    public static function getSubmitionStatus($status, $obj = 'booking')
    {
        $class = 'badge-info';
        switch ($status) {
            case 'Started':
            case 'Updated':
                $class = 'badge-danger';
                if ($obj == 'booking') {
                    $text = 'Booking Incomplete';
                } else {
                    $text = 'Application initiated/updated';
                }
                break;
            case 'Submitted':
                $class = 'badge-success';
                if ($obj == 'booking') {
                    $text = 'Booking Complete';
                } else {
                    $text = 'Application Complete';
                }
                /* case 'Updated':
            $class = "badge-success";
            if ($obj ==  'booking') {
              $text = "Booking Complete";
            } else {
              $text = "Application Updated";
            }
              break; */
        }

        return "<span class ='badge badge-pill $class'>$text</span>";
    }

    public static function getStatusClass($status)
    {
        $class = 'badge-info';

        switch ($status) {
            case 'Started':
                $class = 'badge-danger';
                break;

            case 'Submitted':
                $class = 'badge-success';
                break;

            case 'Updated':
                $class = 'badge-info';
                break;

            case 'Lock':
                $class = 'badge-secondary';
                break;

            case 'Unlock':
                $class = 'badge-dark';
                break;

            case 'Unlock Request':
                $class = 'badge-warning';
                break;

            case 'Archived':
                $class = 'badge-dark';
                break;
        }

        return $class;
    }

    /**
     * Get Field Default Value ( for Parent/Student FirstName, LastName , Email )
     * @param Field $field
     * @return string|null
     */
    public static function getDefaultValue(Field $field)
    {
        if (request()->query->has($field->name)) {
            return request()->query($field->name);
        }
        if (!isset($field->properties['map'])) {
            return null;
        }

        $map = explode('|', $field->properties['map']);

        $student = Auth::guard('student')->user();
        $agent = Auth::guard('agent')->user();

        switch ($map[0]) {
            case 'student':
                if (!$student) {
                    return null;
                }
                if ($map[1] == 'campus') {
                    return $student->{$map[1]}->slug;
                }

                return $student->{$map[1]};
                break;

            case 'parent':
                if (!$student) {
                    return null;
                }
                $parent = $student->parent;

                return optional($parent)->{$map[1]};
                break;

            case 'school':
                if (!$student) {
                    return null;
                }
                $parent = $student->parent;

                return self::extractSchoolDetails($map[1]);
                break;

            case 'booking':
                if ($bookingID = request('booking')) {
                    $booking = Booking::find($bookingID);

                    return self::extractBookingDetails($booking, $map[1]);
                }
                break;

            case 'assistant':
                if ($assiatntID = request('assistant')) {
                    $assiatnt = Assistant::find($assiatntID);
                    return self::extractAssistantDetails($assiatnt, $map[1]);
                }
                break;

            case 'calendar':
                return self::extractCalendarDetails($map[1]);
                break;

            case 'agency':
                return self::extractAgencyDetails($map[1], request('agency'));
                break;

            case 'cart':
                return self::setCartFlag();
                break;

            case 'url':
                $params = Session::get('url_params');
                //return self::setCartFlag();
                break;

            case 'params':
                if (!$student) {
                    return null;
                }
                return isset($student->params[$map[1]]) ? $student->params[$map[1]] : null;
                break;
                break;

            default:
                return null;
                break;
        }
    }

    public static function saveStudentDataFromApplications($field, $data, $student)
    {
        $fields_to_save = ['address', 'city', 'country', 'postal_code', 'phone'];
        if (!isset($field->properties['map'])) {
            return null;
        }
        $map = explode('|', $field->properties['map']);

        if ($map[0] == 'store' && in_array($map[1], $fields_to_save)) {
            switch ($map[1]) {
                case 'address':
                    $student->address = $data;
                    break;
                case 'city':
                    $student->city = $data;
                    break;
                case 'country':
                    $student->country = $data;
                    break;
                case 'postal_code':
                    $student->postal_code = $data;
                    break;
                case 'phone':
                    $student->phone = $data;
                    break;
            }

            $student->save();
        }
    }

    protected static function extractAgencyDetails($term, $agency = null)
    {
        if (!$term || !$agency) {
            return null;
        }

        return $agency->{$term};
    }

    protected static function extractAssistantDetails($assistan, $field)
    {
        if (!$assistan) {
            return null;
        }
        switch ($field) {
            case 'program_title':
                if (isset($assistan->properties['programs'][0]['program'])) {
                    $program = Program::find($assistan->properties['programs'][0]['program']);
                    return $program->title;
                }
                break;

            case 'program_slug':
                if (isset($assistan->properties['programs'][0]['program'])) {
                    $program = Program::find($assistan->properties['programs'][0]['program']);

                    return $program->slug;
                }
                break;

            case 'startDates':
                if (isset($assistan->properties['programs'][0]['program'])) {
                    return $assistan->properties['programs'][0]['start'];
                }
                break;

            case 'campus_slug':
                if (isset($assistan->properties['campuses'][0])) {
                    $campus = Campus::find($assistan->properties['campuses'][0]);

                    return $campus->slug;
                }
                break;

            case 'campus_title':
                if (isset($assistan->properties['campuses'][0])) {
                    $campus = Campus::find($assistan->properties['campuses'][0])->first();
                    return $campus->title;
                }
                break;
        }
    }

    public static function extractSchoolDetails($field)
    {
        switch ($field) {
            case 'language':
                return App::getLocale('lang');
                break;
        }
    }

    protected static function extractBookingDetails($booking, $field)
    {
        //dump($booking->invoice);

        if (!$booking) {
            return;
        }

        switch ($field) {
            case 'program':
                $programs = [];
                if (isset($booking->invoice['details']['courses'])) {
                    foreach ($booking->invoice['details']['courses'] as $course) {
                        $programs[] = $course['title'];
                    }
                }

                return  implode('|', array_unique($programs));
                break;

            case 'extra':
                $data = [];
                if (isset($booking->invoice['details']['courses'])) {
                    foreach ($booking->invoice['details']['courses'] as $course) {
                        foreach ($course['dates'] as $date) {
                            foreach ($date['addons'] as $group => $addons) {
                                if (!empty($addons)) {
                                    $weekAddons = array_column($addons, 'title');
                                    $data[] = $date['start'] . ' - ' . $date['end'] . ': ' . implode(',', $weekAddons);
                                }
                            }
                        }
                    }

                    return  implode('|', $data);
                }
                break;

            case 'activity':
                $data = [];

                if (isset($booking->invoice['details']['courses'])) {
                    foreach ($booking->invoice['details']['courses'] as $course) {
                        foreach ($course['dates'] as $date) {
                            foreach ($date['addons']['activity'] as $addons) {
                                if (!empty($addons)) {
                                    if (isset($addons['title'])) {
                                        $weekAddons = [$addons['title']];
                                    } else {
                                        $weekAddons = array_column($addons, 'title');
                                    }

                                    $data[] = implode(
                                        ',',
                                        $weekAddons
                                    ) . ' : ' . QuotationHelpers::formatDate(
                                        $date['start']
                                    ) . ' - ' . QuotationHelpers::formatDate($date['end']);
                                }
                            }
                        }
                    }

                    return  implode('|', $data);
                }
                break;

            case 'excursion':
                $data = [];

                if (isset($booking->invoice['details']['courses'])) {
                    foreach ($booking->invoice['details']['courses'] as $course) {
                        foreach ($course['dates'] as $date) {
                            foreach ($date['addons']['excursion'] as $addons) {
                                if (!empty($addons)) {
                                    if (isset($addons['title'])) {
                                        $title = $addons['title'];

                                        if (isset($addons['date'])) {
                                            $title .= ' - ' . QuotationHelpers::formatDate($addons['date']);
                                        }
                                        $weekAddons = [$title];
                                    } else {
                                        $weekAddons = array_column($addons, 'title');
                                    }

                                    $data[] = implode(',', $weekAddons);
                                }
                            }
                        }
                    }

                    return  implode('|', $data);
                }
                break;

            case 'startDates':
                $dates = [];
                foreach ($booking->invoice['details']['courses'] as $course) {
                    $courseDates = [];
                    foreach ($course['dates'] as $date) {
                        $courseDates[] = $date['start'] . '-' . $date['end'];
                    }
                    $couseName = $course['title'] . ' - ' . $course['campus_title'];
                    array_push($dates, $couseName . ' : ' . implode(',', $courseDates));
                }

                return implode('|', $dates);
                break;

            case 'totalPrice':
                return $booking->invoice['totalPrice'];
                break;

            case 'coursePrice':
                return $booking->invoice['courses'];
                break;

            case 'extraPrice':
                return $booking->invoice['addons'];
                break;

            case 'totalWeeks':
                return $booking->invoice['details']['weeks'];
                break;

            case 'weeks':
                $weeks = [];
                foreach ($booking->invoice['details']['courses'] as $course) {
                    foreach ($course['dates'] as $date) {
                        $selectedDate = QuotationHelpers::formatDate(
                            $date['start']
                        ) . ' - ' . QuotationHelpers::formatDate($date['end']);
                        $weeks[] = $selectedDate;
                    }
                }

                return implode('|', $weeks);
                break;

            case 'programWeeks':
                $programsWeeks = [];
                foreach ($booking->invoice['details']['courses'] as $course) {
                    $programsWeeks[] = $course['title'] . ':' . $course['totalWeeks'];
                }

                return implode('|', $programsWeeks);
                break;

            case 'campus':
                $campuses = [];
                foreach ($booking->invoice['details']['courses'] as $course) {
                    $campuses[] = $course['campus_title'];
                }

                return implode('|', $campuses);
                break;

            case 'transfer':
                $data = [];
                if (!empty($booking->invoice['details']['transfer'])) {
                    foreach ($booking->invoice['details']['transfer'] as $transfer) {
                        array_push($data, $transfer['option']);
                    }
                }

                return implode('|', $data);

                break;

            case 'transferPrice':
                return $booking->invoice['details']['price']['transfer'];
                break;

            case 'miscellaneous':
                $data = [];
                if (!empty($booking->invoice['details']['miscellaneous'])) {
                    foreach ($booking->invoice['details']['miscellaneous'] as $misc) {
                        array_push($data, $misc['option']);
                    }
                }

                return implode('|', $data);

                break;

            case 'miscPrice':
                return $booking->invoice['details']['price']['miscellaneous'];
                break;

            default:
                return null;
                break;
        }
    }

    private static function extractCalendarDetails($field)
    {
        switch ($field) {
            case 'course_name':
                if (request('course')) {
                    $course = Course::findOrFail(request('course'));

                    return $course->title;
                } else {
                    return null;
                }
                break;
            case 'course_id':
                if (request('course')) {
                    $course = Course::findOrFail(request('course'));

                    return $course->slug;
                } else {
                    return null;
                }
                break;
            case 'end_time':
                if (request('end_time')) {
                    return request('end_time');
                } else {
                    return null;
                }
                break;
            case 'start_time':
                if (request('start_time')) {
                    return request('start_time');
                } else {
                    return null;
                }
                break;
            case 'end_date':
                if (request('end_date')) {
                    return request('end_date');
                } else {
                    return null;
                }
                break;
            case 'start_date':
                if (request('start_date')) {
                    return request('start_date');
                } else {
                    return null;
                }
                break;
            case 'date':
                if (request('start_date') && request('start_time') && request('end_time')) {
                    return QuotationHelpers::formateDateStarTimeEndTime(
                        request('start_date')
                            . ' ' . request('start_time')
                            . ' ' . request('end_time')
                    );
                } elseif (request('start_date') && request('end_date')) {
                    return request('start_date') . ' to ' . request('end_date');
                } else {
                    return null;
                }
                break;
            default:
                return null;
                break;
        }
    }

    public static function extractApplicationCoursesDetailsWithCampus($value)
    {
        $courses = Course::all();
        $program = Program::all();
        $campus = Campus::all();
        $date = Date::all();
        $courseAddons = Addon::all();
        $campusTitle = '';
        $courseTitle = '';
        $dateDetails = '';
        $extractedDetails = '';
        $extractedDetailsArr = [];
        $cour = [];
        $camp = [];
        $prog = [];

        if (is_array($value['campus'])) {
            if (isset($value['courses'])) {
                $count = count($value['courses']);
                foreach ($value as $key => $val) {
                    foreach ($val as $k => $v) {
                        if ($key == 'campus') {
                            $c = $campus->firstWhere('id', $value['campus'][$k]);
                            $camp[$k] = $c['title'];
                        }
                        if ($key == 'courses') {
                            if (isset($value['date'][$value['courses'][$k]]) || isset($value['date_addons'][$value['courses'][$k]])) {
                                //if(isset($value['date'][$v]) || isset($value['date_addons'][$v])){
                                $dateId = $value['date'][$value['courses'][$k]];
                                unset($value['date'][$value['courses'][$k]]);
                                $dateArray = $date->firstWhere('id', $dateId);
                                if ($dateArray['date_type'] == 'specific-dates') {
                                    //  $value['date'][$k] = $date['properties']['start_date'] ." - ". $date['properties']['end_date'];
                                    $dateDetails = $dateArray['properties']['start_date'] . ' - ' . $dateArray['properties']['end_date'];
                                } else {
                                    $dateDetails = $dateArray['properties']['date'];
                                }

                                $cou = $courses->firstWhere('slug', $v);
                                $cour[$k] = $cou['title'] . ' (' . $dateDetails . ')';

                                if (isset($value['date_addons'][$value['courses'][$k]])) {
                                    foreach ($value['date_addons'][$value['courses'][$k]] as $adKey => $addon) {
                                        $date_addons[] = $date['properties']['addons'][$adKey]['title'];
                                    }
                                    $date_addons = implode('|', $date_addons);
                                    $cour[$k] .= '(' . $date_addons . ')';
                                }

                                if (isset($value['course_addons'][$v])) {
                                    foreach ($value['course_addons'][$v] as $cadKey => $addon) {
                                        $couAdd = $courseAddons->firstWhere('key', $cadKey);
                                        $course_addons[] = $couAdd['title'];
                                    }
                                    $course_addons = implode('|', $course_addons);
                                    $cour[$k] .= '(' . $course_addons . ')';
                                }
                            } else {
                                $cou = $courses->firstWhere('slug', $value['courses'][$k]);
                                $cour[$k] = $cou['title'];
                            }
                        }
                    }
                }

                foreach ($cour as $k => $co) {
                    $extractedDetailsArr[$k] = $camp[$k] . ' - ' . $co;
                }
            }
            if (isset($value['programs'])) {
                $count = count($value['programs']);
                foreach ($value as $key => $val) {
                    foreach ($val as $k => $v) {
                        if ($key == 'campus') {
                            $c = $campus->firstWhere('id', $value['campus'][$k]);
                            $camp[$k] = $c['title'];
                        }
                        if ($key == 'programs') {
                            if (isset($value['date'][$value['programs'][$k]])) {
                                $dateId = $value['date'][$value['programs'][$k]];
                                unset($value['date'][$value['programs'][$k]]);
                                $dateArray = $date->firstWhere('id', $dateId);
                                if ($dateArray['date_type'] == 'specific-dates') {
                                    $value['date'][$k] = $date['properties']['start_date'] . ' - ' . $date['properties']['end_date'];
                                } else {
                                    $value['date'][$k] = $date['properties']['date'];
                                }

                                $pro = $program->firstWhere('slug', $value['programs'][$k]);
                                $prog[$k] = $pro['title'] . ' (' . $value['date'][$k] . ')';
                            } else {
                                $pro = $program->firstWhere('slug', $value['programs'][$k]);
                                $prog[$k] = $pro['title'];
                            }
                        }
                    }
                }

                foreach ($prog as $k => $po) {
                    $extractedDetailsArr[$k] = $camp[$k] . ' - ' . $po;
                }
            }
        } else {
            //if(!is_array($value['campus']) || !is_array($value['programs']) || !is_array($value['courses'])){
            if (isset($value['programs'])) {
                $po = $program->firstWhere('slug', $value['programs']);
                $programTitle = $po['title'];
                $extractedDetails .= $programTitle;
                if (isset($value['campus'])) {
                    $campusVal = $value['campus'];
                    $c = $campus->filter(function ($camp) use ($campusVal) {
                        return $camp->id == $campusVal || $camp->slug == $campusVal;
                    });
                    $c = $c->first();
                    $campusTitle = $c->title;
                    $extractedDetails .= ' - ' . $campusTitle;
                }

                if (isset($value['date']) || isset($value['program_addons'])) {
                    $program_date = isset($value['date']) ? $value['date'] : '';
                    $program_addons = isset($value['program_addons']) ? $value['program_addons'] : '';
                    if ($program_addons) {
                        $addons = implode('|', $program_addons);
                        //$extractedDetails = $programTitle . " ". $program_date . " " . " Addons: " . $addons;
                        $extractedDetails .= ' (' . $program_date . ') ' . ' Addons: ' . $addons;
                    } else {
                        $extractedDetails .= ' (' . $program_date . ')';
                    }
                }
                //$extractedDetails = $campusTitle .", ".$programTitle;
                $extractedDetailsArr[] = $extractedDetails;
            }

            /*if(isset($value['courses'])){
                $co = $courses->firstWhere('slug', $value['courses']);
                $coursesTitle = $co['title'];
                if(isset($value['campus'])){
                    $c = $campus->firstWhere('id', $value['campus']);
                    $campusTitle = $c['title'];
                }
                $extractedDetails = $campusTitle .", ".$coursesTitle;
                $extractedDetailsArr[] = $extractedDetails;

            }*/
        }

        return $extractedDetailsArr;
    }

    public static function getCampusName($campusId = null)
    {
        if ($campus = Campus::where('id', (int) $campusId)->orWhere('slug', $campusId)->first()) {
            return $campus->title;
        }
        return $campusId;
    }

    public static function getProgramName($program = null)
    {
        if ($program = Program::where('id', (int) $program)->orWhere('slug', $program)->first()) {
            return $program->title;
        }
        return $program;
    }

    public static function getCourseName($course = null)
    {
        if ($course = Course::where('id', (int) $course)->orWhere('slug', $course)->first()) {
            return $course->title;
        }
        return $course;
    }

    public static function getStagesList()
    {
        return [
            'applicant'     => 'Applicant',
            'student'         => 'Student',
        ];
    }

    public static function extractCustomFields($list)
    {
        $list = Arr::pluck($list, 'name');
        $customFields = CustomField::whereIn('slug', $list)->pluck('name', 'slug')->toArray();
        return $customFields;
    }

    public static function extractApplicationCoursesDetailsWithoutCampus($value)
    {
        $courseTitle = '';
        $dateDetails = '';
        $extractedDetails = '';
        $extractedDetailsArr = [];
        $cour = [];
        $prog = [];

        if (isset($value['programs'])) {
            if (!$pro = Program::where('slug', $value['programs'])->first()) {
                return [];
            }
            $programTitle = $pro['title'];
            $extractedDetails .= $programTitle;
            if (isset($value['date']) || isset($value['program_addons'])) {
                $program_date = isset($value['date']) ? $value['date'] : '';
                $program_addons = isset($value['program_addons']) ? $value['program_addons'] : '';
                if ($program_addons) {
                    $addons = implode('|', $program_addons);
                    //$extractedDetails = $programTitle . " ". $program_date . " " . " Addons: " . $addons;
                    $extractedDetails .= ' (' . $program_date . ') ' . ' Addons: ' . $addons;
                } else {
                    $extractedDetails .= ' (' . $program_date . ')';
                }
            }/*else{
                $po = $program->firstWhere('slug', $value['programs']);
                $extractedDetails = $programTitle;
            }*/

            $extractedDetailsArr[] = $extractedDetails;
        }

        if (isset($value['courses'])) {

            foreach ($value as $key => $val) {
                if (is_array($val)) {
                    foreach ($val as $k => $v) {
                        $cou = Course::where('slug', $v)->first();
                        $cour[$k] = $cou['title'];
                        if (isset($value['date'][$v])) {
                            $dateId = isset($value['date'][$v]) ? $value['date'][$v] : '';
                            unset($value['date'][$v]);

                            $dateArr = Date::where('id', $dateId)->first();

                            if ($dateArr['date_type'] == 'specific-dates') {
                                $dateDetails = $dateArr['properties']['start_date'] . ' - ' . $dateArr['properties']['end_date'];
                            } else {
                                $dateDetails = $dateArr['properties']['date'];
                            }
                            $cour[$k] .= ' (' . $dateDetails . ')';
                        }
                    }
                }
            }
            foreach ($cour as $k => $co) {
                $extractedDetailsArr[$k] = $co;
            }
        }

        return $extractedDetailsArr;
    }

    public static function newSubmissionStatus(Submission $submission, $status, $step = null, $user = null)
    {
        $submission->status = $status;
        $submission->save();

        $submissionStatus = SubmissionStatus::create(
            [
                'submission_id' => $submission->id,
                'student_id' => $user == null ? $submission->student->id : null,
                'status' => $status,
                'step' => $step,
                'properties' => [
                    'by'            => $user == null ? 'Student' : 'User',
                    'name'          => $user == null ? $submission->student->name : $user->name,
                    'submitted_at'  => Carbon::now(),
                ],
            ]
        );
        if ($submissionStatus) {
            // Dispatch Subission Status Changed Event
            event(new SubmissionStatusChanged($submission, $submissionStatus));
        }

        return $submissionStatus;
    }

    public static function getTextSubmissionStatus(Application $application)
    {
        $status = 'Account Created';

        if (count($application->submissions[0]->statuses) > 0) {
            $status = $application->submissions[0]->statuses->last()->status;
        }

        if ($status == 'Updated') {
            $status = 'In Progress';
        }
        if ($status == 'Lock') {
            $status = 'Locked';
        }
        if ($status == 'Unlock Request') {
            $status = 'Unlock Requested';
        }
        if ($status == 'Unlock') {
            $status = 'Unlocked';
        }

        return ApplicationStatusHelpers::statusLabel($status);
    }

    public static function updateSubmissionStatus($submission, $submissionStatus, $data)
    {
        $status = SubmissionStatus::firstOrCreate(
            [
                'submission_id' => $submission->id,
                'student_id'    => $submission->student->id,
                'status'        => $submissionStatus,
            ]
        );

        $status->properties = $data;

        if ($status->save()) {
            $submission->status = $submissionStatus;
            $submission->save();
            return true;
        }

        return false;
    }

    public static function getLink(Submission $submission)
    {
        return md5($submission->created_at . $submission->uuid . $submission->id);
    }


    public static function exctractProgramValueFromSubmission($programFieldNames, $submission)
    {
        $programValue = null;

        if (!is_array($programFieldNames)) {
            $programFieldNames = [];
        }

        if (is_array($programFieldNames) && count($programFieldNames) == 1) {

            $programValue = isset($submission->data[$programFieldNames[0]]) ? $submission->data[$programFieldNames[0]] : null;
        } else {
            if ($submission instanceof Submission && isset($submission->data)) {
                foreach ($submission->data as $key => $value) {
                    if (in_array($key, $programFieldNames) && isset($value)) {
                        $programValue = $value;
                    }
                }
            }
        }
        if (isset($programValue)) {
            if (is_array($programValue)) {

                if (isset($programValue['program_name'])) {
                    $programValue = $programValue['program_name'];

                } elseif(isset($programValue['programs'])) {
                    if($program = Program::where('id', $programValue['programs'])->orWhere('slug', $programValue['programs'])->first()){
                        $programValue = $program->title;
                    }
                }else{
                    $programValue = "";
                }
            }
        }
        return $programValue;
    }


    public static function extractFieldLabel($field)
    {
        $props = $field->properties;

        if (isset($props['placeholder']) && $props['placeholder'] != '') {
            return $props['placeholder'];
        }

        if (isset($props['label']['text']) && $props['label']['text'] != '') {
            return $props['label']['text'];
        }
        return $field->label;
    }
}
