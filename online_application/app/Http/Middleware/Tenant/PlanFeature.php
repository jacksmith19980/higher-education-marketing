<?php

namespace App\Http\Middleware\Tenant;

use App\Helpers\Plan\PlanHelpers;
use Carbon\Carbon;
use Closure;

class PlanFeature
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next, $features)
    {
        /* if (\Auth::user()->hasRole('Super Admin')) {
            return $next($request);
        } */

        $plan = isset(session('settings-'.session('tenant'))['plan']) ? session('settings-'.session('tenant'))['plan'] :
        null;

        if($plan){
            if(empty(array_intersect($plan['features'], (array) $features)))
            {
                return redirect(PlanHelpers::REDIRECTIO_ON_FAIL);
            }
        }
        return $next($request);
    }
}
