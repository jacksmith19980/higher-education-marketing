<?php

namespace App\Http\Controllers\Tenant\School\Agent;

use App\Events\Tenant\Agency\AgencyApplicationSubmitted;
use App\Events\Tenant\Agency\AgencyApplicationUpdated;
use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Agency;
use App\Tenant\Models\AgencySubmission;
use App\Tenant\Models\Application;
use Illuminate\Http\Request;

class AgencySubmissionsController extends Controller
{
    public function submit(School $school, Agency $agency, Application $application , Request $request , $redirect = true)
    {


        // Update the Application
        if ($request->has('submission_id')) {
            $submission = AgencySubmission::find($request->submission_id);
            $submission->data = $request->except('_token');
            $submission->status = 'Updated';
            $submission->properties = [
            'updated' => date('Y-m-d h:i:s'),
         ];
            $submission->save();
            event(new AgencyApplicationUpdated($submission, $application, $agency, $school));
        } else { // Submit new application

            $submission = new AgencySubmission();
            $submission->data = $request->except('_token');
            $submission->status = 'New';
            $submission->application()->associate($request->application_id);
            $submission->agency()->associate($request->agency_id);
            $submission->save();

            event(new AgencyApplicationSubmitted($submission, $application, $agency, $school));
        }
        if($redirect){
            return redirect(route('school.agent.agency.edit', [
             'school' => $school,
             'agency' => $agency,
            ]));
        }else{
            return $submission;
        }
    }
}
