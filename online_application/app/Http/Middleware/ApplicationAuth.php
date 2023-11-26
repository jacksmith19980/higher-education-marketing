<?php

namespace App\Http\Middleware;

use App\School;
use App\Tenant\Models\Application;
use App\Tenant\Models\Student;
use Closure;
use Hash;

class ApplicationAuth
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if ($application = Application::bySlug($request->application)->first()) {
            // check if application required Login
            if ($application->login) {
                // Redirect to School Login
                $school = School::bySlug($request->school)->first();

                return redirect(route('school.home', $school));
            } else {

            }
        }

        return $next($request);
    }
}
