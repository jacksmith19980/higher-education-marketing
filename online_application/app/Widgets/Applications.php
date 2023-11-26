<?php

namespace App\Widgets;

use App\Tenant\Models\Submission;
use App\Widgets\Widgets;
use Response;

class Applications extends Widgets
{
    public function build()
    {
        $applications = Submission::get();

        if ($total = $applications->count()) {
            $complete = $applications->where('status', 'Submitted')->count();
            $completePercentage = ($complete / $total) * 100;

            $incomplete = $applications->where('status', '!=', 'Submitted')->count();
            $incompletePercentage = ($incomplete / $total) * 100;
        } else {
            $total = 0;
            $complete = 0;
            $completePercentage = 0;
            $incomplete = 0;
            $incompletePercentage = 0;
        }

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'data'      => [
                'total'     => $total,
                'complete'  => [
                    'count'         => $complete,
                    'percentage'    => $completePercentage,
                ],
                'incomplete' => [
                    'count'         => $incomplete,
                    'percentage'    => $incompletePercentage,
                ],
            ],
        ]);
    }
}
