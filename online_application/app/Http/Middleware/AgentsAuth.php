<?php

namespace App\Http\Middleware;

use App\School;
use Auth;
use Closure;

class AgentsAuth
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
        if (! Auth::guard('agent')->user()) {
            return redirect(route('school.agent.login', ['school' => $request->school]));
        }

        return $next($request);
    }
}
