<?php

namespace App\Http\Middleware\Tenant;

use App\School;
use Closure;

class SwitchTenant
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

        // Get school

        $school = School::bySlug($request->school)->first();

        // If No Tenant

        if (! $school) {
            return $next($request);
        }

        //Dispatch Event When new School is Created

        //event(new TenantIdentified($school));

        return $next($request);
    }
}
