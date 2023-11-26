<?php

namespace App\Http\Middleware\Tenant;

use App\Events\Tenant\TenantIdentified;
use App\Repositories\Interfaces\SchoolRepositoryInterface;
use App\School;
use Auth;
use Closure;
use Request;
use Route;

class SetTenant
{
    protected $schoolRepository;

    public function __construct(SchoolRepositoryInterface $schoolRepository)
    {
        $this->schoolRepository = $schoolRepository;
    }

    /**
     * Handle an incoming request.
     *
     * @param \Illuminate\Http\Request $request
     * @param \Closure $next
     * @return mixed
     */
    public function handle($request, Closure $next)
    {
        if (session('tenant')) {
            // Get the Tenant Using the tenant uuid stored in session
            $tenant = $this->resolveTenant(session('tenant'), null);

        }else{

            $tenant = $this->resolveTenant(null, $request->school);
        }

        // If No Tenant
        if (! $tenant) {
            return redirect('/');
        }

        //if the user don't belongs to the School
        if ($user = auth()->user()) {
            if (! $user->schools->contains('id', $tenant->id)) {
                return redirect('/home');
            }
        }

        //Dispatch Event When new School is Created
        event(new TenantIdentified($tenant));

        return $next($request);
    }

    protected function resolveTenant($uuid = null, $slug = null)
    {
        if ($uuid) {
            $school = $this->schoolRepository->byUuid($uuid)->first();
        }

        if ($slug) {
            $school = $this->schoolRepository->bySlug($slug)->first();
        }

        if (! isset($school) || ! $school) {
            return false;
        }

        if ($school) {
            session()->put('school', $school);

            return $school;
        }

        return false;
    }
}
