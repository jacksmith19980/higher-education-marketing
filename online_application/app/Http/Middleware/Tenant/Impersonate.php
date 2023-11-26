<?php

namespace App\Http\Middleware\Tenant;

use Auth;
use Closure;
use Session;

class Impersonate
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

        // Agent Impersonate Student
        if ($id = Session::get('impersonate')) {
            Auth::guard('student')->onceUsingId($id);

        // Parent Impersonate Child
        } elseif ($id = Session::get('child-impersonate')) {

            Auth::guard('student')->onceUsingId($id);

        }elseif ($id = Session::get('impersonate-instructor')) {
            Auth::guard('instructor')->onceUsingId($id);
        }
        return $next($request);
    }
}
