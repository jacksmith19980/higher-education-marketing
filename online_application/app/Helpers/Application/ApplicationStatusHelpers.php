<?php

namespace App\Helpers\Application;

use App\Tenant\Models\ApplicationStatus;
use App\Tenant\Models\Submission;
use Illuminate\Support\Arr;

class ApplicationStatusHelpers
{
    public static function statusLabel($status_text)
    {
        $applicationStatus = ApplicationStatus::where('title', $status_text)->first();
        if ($applicationStatus) {
            if (empty($applicationStatus->label)) {
                $status = $status_text;
            } else {
                $status = $applicationStatus->label;
            }
        } else {
            $status = $status_text;
        }
        return $status;
    }

    public static function getApplicationStatusesList()
    {
        $applicationStatuses = ApplicationStatus::pluck('label', 'title')->toArray();

        foreach ($applicationStatuses as $title=>$label) {
            if (empty($label)) {
                $applicationStatuses[$title] = $title;
            }
        }

        return $applicationStatuses;
    }

    public static function getStatuses()
    {
        $used_statuses = Submission::select('status')->distinct()->get()->pluck('status')->toArray();
        return array_unique(array_merge(ApplicationStatus::get()->pluck('title', 'id')->toArray(), $used_statuses));
    }

    public static function getStatusesDoubleTitle()
    {
        $application_status_title = Arr::pluck(ApplicationStatus::select(['title'])->get()->toArray(), 'title');
        $used_statuses = Submission::select('status')->distinct()->get()->pluck('status')->toArray();
        $application_status_title = array_filter(array_unique(array_merge($application_status_title, $used_statuses)));

        $callback = function (string $k): string {
            return ApplicationStatusHelpers::statusLabel($k);
        };

        $application_status_label = array_map($callback, $application_status_title);

        $application_status = array_combine($application_status_title, $application_status_label);

        return $application_status;
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
}
