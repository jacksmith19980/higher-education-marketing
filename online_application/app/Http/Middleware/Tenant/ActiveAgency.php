<?php

namespace App\Http\Middleware\Tenant;

use Auth;
use Closure;

class ActiveAgency
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
        $school = $request->school;
        $agent = Auth::guard('agent')->user();
        $agency = $agent->agency;
        if (! $agency->approved) {
            return redirect(route('school.agent.agency.edit', [
                'school' => $school,
                'agency' => $agency,
            ]));
        }

        return $next($request);
    }
}
