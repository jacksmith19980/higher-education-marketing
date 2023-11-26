<?php

namespace App\Http\Middleware;

use Closure;

class SuperAdminMiddleware
{
    public function handle($request, Closure $next)
    {
        if (! Auth::guard('web')->user()) {
            return redirect(route('school.agent.login', ['school' => $request->school]));
        }

        return $next($request);
    }
}
