<?php

namespace App\Http\Middleware;

use Closure;
use App\School;
use Illuminate\Support\Facades\Auth;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Session;

class RedirectIfAuthenticated
{
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  $guard
     * @return mixed
     */
    public function handle($request, Closure $next, $guard = null)
    {
        $school = School::where('slug', $request->school)->first();

        switch ($guard) {
        case 'student':
          if (Auth::guard($guard)->check()) {
              $intendedUrl = Session::get('intended_url');
              Session::forget('intended_url');
              if($intendedUrl){
                return redirect()->to($intendedUrl);
              }
              return redirect()->route('school.home', $school);
          }
          break;

        case 'agent':
          if (Auth::guard($guard)->check()) {
              return redirect()->route('school.agent.home', $school);
          }
          break;
        default:
          if (Auth::guard($guard)->check()) {
              return redirect($this->redirectTo);
          }
          break;
      }

        return $next($request);
    }
}
