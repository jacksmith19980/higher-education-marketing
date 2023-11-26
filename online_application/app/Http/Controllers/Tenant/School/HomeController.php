<?php

namespace App\Http\Controllers\Tenant\School;

use App\Http\Controllers\Controller;
use App\School;
use App\Tenant\Models\Application;
use Auth;

class HomeController extends Controller
{
    public function index(School $school)
    {
        session()->put('tenant', $school->uuid);
        // Redirect to first application
        $applications = Application::published()->first();
        $application = null;

        if($applications){
            $applications->each(function ($app) use ($application) {
                if (isset($app->properties['no_login'])) {
                    $application = $app;
                }
            });
        }
        // check if parent
        $role = Auth::guard('student')->user()->role;

        if ($role == 'parent') {
            return redirect(route('school.parents', $school));
        } elseif ($application) {
            return redirect(route('application.show', [
            'school'        => $school,
            'application'   => $application,
            ]));
        } else {
            return redirect(route('application.index', ['school' => $school]));
        }
    }
}
