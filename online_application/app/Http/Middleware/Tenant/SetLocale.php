<?php

namespace App\Http\Middleware\Tenant;

use App;
use Auth;
use App\Tenant\Models\Setting;
use Carbon\Carbon;
use Closure;

class SetLocale
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
        // Save Lang in Cookies
        if ($request->has('lang')) {
            $response = $next($request);
            $lang = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $request->lang));

            Carbon::setLocale($lang);
            App::setLocale($lang);

            return $next($request)->withCookie(cookie()->forever('lang-'.session('tenant'), $request->lang));
        }

        // Get Lang From Cookies if Exist
        if ($locale = $request->cookie('lang-'.session('tenant'))) {
            $locale = strtolower(preg_replace('/[^a-zA-Z0-9-_\.]/','', $locale));
            Carbon::setLocale($locale);
            App::setLocale($locale);
            return $next($request);
        }

        $language = $this->getLanguage();
        Carbon::setLocale($language);
        App::setLocale($language);
        return $next($request);
    }


    protected function getLanguage()
    {
        $user = $user = Auth::guard('web')->user();
        if(isset($user)){
            if($lang = $user->language){
                return $lang;
            }
        }

        // Getting School Settings
        if (session('settings-'.session('tenant'))) {
            $settings = session('settings-'.session('tenant'));
        } else {
            $settings = Setting::byGroup();
        }
        return isset($settings['school']['locale']) ? $settings['school']['locale'] : 'en';
    }
}
