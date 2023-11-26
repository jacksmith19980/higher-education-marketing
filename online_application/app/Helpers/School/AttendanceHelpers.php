<?php

namespace App\Helpers\School;

use App\Tenant\Models\Attendance;
use Illuminate\Support\Arr;

class AttendanceHelpers
{


    public static function getDefaultStatusesList()
    {
        return [
            "présent - classe"      => "Present - Class",
            "absent"                =>  "Absent",
            "retard"                =>  "Retard",
            "présent - en ligne"    => "Present - Online",
            "withdrawn"             =>  "Withdrawn"
        ];
    }
    public static function getStatuses()
    {
        $used_statuses = Attendance::select('status')->distinct()->get()->pluck('status')->toArray();
        return $used_statuses;
    }

    public static function lastStatusByUpdateDate($statuses)
    {
        $statuses =  $statuses->get()->keyBy('updated_at')->toArray();
        if (!empty($statuses)) {
            ksort($statuses);
            return end($statuses);
        }
        return ['status' => 'In Progress'];
    }

	  public static function getAttendanceInArrayOnlyStatusId()
    {
        $attendance_statuses = Arr::pluck(Attendance::select('status', 'id')->get()->toArray(), 'status', 'id');

		return $attendance_statuses;
    }

	  public static function getAttendanceInArrayOnlyStatus()
    {
        $attendance_status_label = Arr::pluck(Attendance::select('status')->get()->toArray(), 'status');

		return $attendance_status_label;
    }

	  public static function getStatusesDoubleTitle()
    {
        $attendance_status_label = Arr::pluck(Attendance::select('status')->get()->toArray(), 'status');

        $application_status_arr = array_combine($attendance_status_label, $attendance_status_label);

        return $application_status_arr;
    }
}
