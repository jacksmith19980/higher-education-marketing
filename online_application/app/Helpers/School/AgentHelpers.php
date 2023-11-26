<?php

namespace App\Helpers\School;

use App\Tenant\Models\Application;
use App\Tenant\Models\Student;
use Illuminate\Support\Facades\Auth;

/**
 * Application Helper
 */
class AgentHelpers
{
    /**
     * Get publised applications list
     */
    public static function getPublishedApplications($listView = true)
    {
        $list = [];
        $applications = Application::published()->get();

        $applications = $applications->filter(function ($application) {
            if (isset($application->properties['enable_for_agents'])) {
                return $application;
            }
        });

        if (! $listView) {
            return $applications;
        }

        if ($applications) {
            foreach ($applications as $application) {
                $list[$application->id] = $application->title;
            }

            return $list;
        }

        return $list;
    }

    public static function getApplications()
    {
        $applications = Application::student()->get();

        $applications = $applications->filter(function ($application) {
            if (isset($application->properties['enable_for_agents'])) {
                return $application;
            }
        });

        return $applications;
    }

    public static function getApplicationsList($application = null)
    {
        if ($application) {
            return $application;
        }

        $list = [
            null => __('Select Application'),
        ];

        //$list = [];
        $applications = self::getApplications();
        dd($applications);
        foreach ($applications as $application) {
            $list[$application->id] = $application->title;
        }

        return $list;
    }

    public static function getStudentsList($filter = [])
    {
        $students = self::getStudents($filter);

        return $students->orderBy('students.created_at', 'desc')->with('submissions')->paginate(50);
    }

    public static function getStudents($filter = [])
    {
        $agent = Auth::guard('agent')->user();

        if ($agent->roles == 'Super Admin') {
            $students = Student::select('*');
        } elseif ($agent->roles == 'Agency Admin') {
            $students = $agent->agency()->first()->students();
        } elseif ($agent->roles == 'Regular Agent') {
            $students = $agent->students();
        }

        if (! empty($filter) && ! empty($students)) {
            $students->where($filter);
        }

        return $students;
    }

    public static function existingsRoles($rol = '')
    {
        $roles = ['Regular Agent', 'Agency Admin', 'Super Admin'];
        $i = array_search($rol, $roles);

        return $i == false ? $roles : array_slice($roles, 0, $i + 1);
    }
}
