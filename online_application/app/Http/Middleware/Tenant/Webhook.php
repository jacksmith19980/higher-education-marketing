<?php

namespace App\Http\Middleware\Tenant;

use App\Events\Tenant\TenantIdentified;
use App\School;
use Closure;

class Webhook
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
        $tenant = $this->resolveTenant($request->school);

        //Dispatch Event When new School is Created
        event(new TenantIdentified($tenant));

        return $next($request);
    }

    protected function resolveTenant($slug = null)
    {
        if ($slug) {
            return School::where('slug', $slug)->first();
        }

        return false;
    }
}
