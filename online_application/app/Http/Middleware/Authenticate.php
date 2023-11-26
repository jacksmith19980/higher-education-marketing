<?php

namespace App\Http\Middleware;

use Illuminate\Auth\Middleware\Authenticate as Middleware;
use Session;

class Authenticate extends Middleware
{
    protected $guards;

    public function handle($request, $next, ...$guards)
    {
        $this->guards = $guards;

        return parent::handle($request, $next, ...$guards);
    }

    /**
     * Get the path the user should be redirected to when they are not authenticated.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return string
     */
    protected function redirectTo($request)
    {
        if (! $request->expectsJson()) {
            if(array_key_exists(0, $this->guards) && $this->guards[0] == 'student'){


                $params = $request->all();
                $params['school'] = $request->school;
                Session::put('url_params' , $params);

                
                $intendedURL = $request->fullUrl();
                Session::put('intended_url' , $intendedURL);

            return route('school.register', $params);

            }else{
            return route('login');
            }
        }
    }
}
