<?php

namespace App\Http\Middleware;

use Auth;
use Closure;

class InstructorsAuth
{
    public function handle($request, Closure $next)
    {
        if (! Auth::guard('instructor')->user()) {
            return redirect(route('school.instructor.login', ['school' => $request->school]));
        }

        return $next($request);
    }
}
