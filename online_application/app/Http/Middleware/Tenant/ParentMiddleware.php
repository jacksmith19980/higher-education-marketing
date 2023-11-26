<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use Session;

class ParentMiddleware
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
        // check if user is child stop impersonating
        $user = \Auth::guard('student')->user();

        if ($user->parent_id) {
            // Stop impersonating
            Session::forget('child-impersonate');

            return redirect(route('school.parents', $request->school));
        }

        return $next($request);
    }
}
