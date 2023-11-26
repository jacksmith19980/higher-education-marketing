<?php

namespace App\Http\Controllers\Tenant\School\Agent;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Submission;
use Auth;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use PDF;
use Storage;

class PDFController extends Controller
{
    public function pdf(School $school, Submission $submission, $action)
    {
        // check if the agent is the owner of the student
        if ($submission->student->agent->id != Auth::guard('agent')->user()->id) {
            return redirect()->back()->withError('Somthing Went Wrong');
        }

        $application = $submission->application()->with('sections.fields')->first();

        if ($action == 'download') {
            $name = Str::slug($submission->student->name).'-'.$application->slug.'-'.time().'.pdf';

            return PDF::loadView('back.students.submission-pdf', ['submission'=> $submission, 'application' => $application])->download($name);
        }
    }
}
