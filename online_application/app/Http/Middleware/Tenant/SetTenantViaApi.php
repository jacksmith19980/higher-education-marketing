<?php

namespace App\Http\Middleware\Tenant;

use Closure;
use App\School;
use App\Tenant\Models\ApiKey;
use App\Events\Tenant\TenantIdentified;
use F9Web\ApiResponseHelpers;

class SetTenantViaApi
{
    use ApiResponseHelpers;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if(!$apiKey = $request->bearerToken()){
            return $this->respondUnAuthenticated('Unauthenticated!');
        }

        if(!$tenant = $this->resolveTenant($request , $apiKey)){
            return $this->respondUnAuthenticated('Unauthenticated!');
        }

        //Dispatch Event When new School is Created
        event(new TenantIdentified($tenant));

        return $next($request);
    }

    protected function resolveTenant($request, $apiKey = null)
    {
        $school = School::bySlug($request->school)->first();
        if(optional($school->apiKeys()->isActive()->first())->api_key == $apiKey){
            return $school;
        }
        return null;
    }
}
