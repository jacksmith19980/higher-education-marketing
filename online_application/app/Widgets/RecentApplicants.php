<?php

namespace App\Widgets;

use App\Http\Resources\School\ApplicantsResource;
use App\Tenant\Models\Student;
use App\Tenant\Models\Submission;
use App\Widgets\Widgets;
use Response;

class RecentApplicants extends Widgets
{
    public function build()
    {

        //$applicants = Submission::take(5)->with('student')->get()->pluck('student');
        $applicants = Student::orderBy('id', 'DESC')->take(5)->get();

        return Response::json([
            'status'    => 200,
            'response'  => 'success',
            'data'      => [
                'applicants' => ApplicantsResource::collection($applicants),
            ],
        ]);
    }
}
