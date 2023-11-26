<?php

namespace App\Http\Controllers\Tenant\School;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Student;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class StudentStageWebhookController extends Controller
{
    public function response(School $school, Request $request)
    {
        session()->put('tenant', $school->uuid);
        $request = $request->all();

        $stage = ($request['mautic.lead_post_save_update'][0]['stage']) ? $request['mautic.lead_post_save_update'][0]['stage']['name'] : null;

        if(!$stage){
            return;
        }

        $email = $request->all()['mautic.lead_post_save_update'][0]['lead']['fields']['core']['email']['value'];

        $student = Student::where('email', $email)->first();

        if ($student) {
            $student->admission_stage = $stage;
            $student->save();
        }
    }
}
