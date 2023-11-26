<?php

namespace App\Http\Controllers\Tenant\School;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Submission;
use Illuminate\Http\Request;
use Response;

class SubmissionController extends Controller
{
    public function showReview(School $school, Submission $submission)
    {
        return view(
            'front.submission.review',
            compact('submission')
        );
    }

    public function review(Request $request, School $school, Submission $submission)
    {
        $student = auth()->guard('student')->user();
        $agent = auth()->guard('agent')->user();
        $user = auth()->user();

        if (! $student && ! $user && ! $agent) {
            abort(401);
        }

        $application = $submission->application;
        $excludes = ['helper', 'hidden'];
        $can_edit =  false;
        $isAdmin = false;
        $studentView = true;

        $html = view(
            'front.applications.application-layouts.rounded.review.content',
            compact('submission', 'application', 'excludes','studentView' , 'can_edit' , 'isAdmin')
        )->render();


        return Response::json([
            'status' => 200,
            'response' => 'success',
            'extra' => [
                'html' => $html,
            ],
        ]);
    }
}
