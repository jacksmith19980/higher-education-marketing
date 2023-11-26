<?php

namespace App\Providers;

use Auth;
use Blade;
use Session;
use App\Helpers\School\SchoolHelper;
use Illuminate\Pagination\Paginator;
use Illuminate\Support\Facades\View;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\ServiceProvider;
use App\Http\ViewComposers\StudentViewComposer;
use App\Http\ViewComposers\SettingsViewComposer;
use App\Http\ViewComposers\QuotationViewComposer;
use App\Http\ViewComposers\NavigationViewComposer;
use App\Http\ViewComposers\SchoolAuthViewComposer;
use App\Http\ViewComposers\NotificationsViewComposer;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(191);

        //\Illuminate\Pagination\Paginator::useBootstrap();

        Paginator::useBootstrap();

        // View composers
        View::composer('back.layouts._partials.top-nav', NavigationViewComposer::class);

        View::composer('*', SchoolAuthViewComposer::class);
        View::composer('*', StudentViewComposer::class);
        View::composer('*', SettingsViewComposer::class);

        View::composer('*/quotation/*', QuotationViewComposer::class);

        View::composer('back.layouts._partials.notifications', NotificationsViewComposer::class);

        // Blad Directives

        // check if select option value euqlas givin value
        Blade::if('selected', function ($value, $v) {
            return $value == $v;
        });

        // Check if Smart Field
        Blade::if('smart', function ($smart) {
            return isset($smart) && ! empty($smart);
        });

        // Check if Smart Field
        Blade::if('sync', function ($sync) {
            return isset($sync) && ! empty($sync);
        });

        // Check if radio is checked
        Blade::if('checked', function ($value, $v) {
            return $value == $v;
        });

        // Check if Field has Label
        Blade::if('label', function ($label) {
            return (isset($label)) ? $label : false;
        });

        // Check if Field has Placeholder
        Blade::if('placeholder', function ($placeholder) {
            return (isset($placeholder)) ? $placeholder : false;
        });

        // Check if Field has Placeholder
        Blade::if('required', function ($required) {
            return (isset($required)) ? $required : false;
        });

        // Check if Field has Placeholder
        Blade::if('checked', function ($default, $value) {
            return $default == $value;
        });

        //Blade::component('app.components.alert', 'logo');

        // Check if Field has Placeholder
        Blade::if('impersonate', function () {
            return Session::has('impersonate') || Session::has('child-impersonate');
        });

        Blade::if('impersonateStudent', function () {
            return Session::has('impersonate');
        });

        Blade::if('impersonateChild', function () {
            return Session::has('child-impersonate');
        });

        Blade::if('admin', function () {
            return Auth::guard('agent')->user()->is_admin;
        });

        Blade::if('superAdmin', function () {
            return Auth::guard('agent')->user()->roles == 'Super Admin';
        });

        Blade::if('agentAdmin', function () {
            $rol = Auth::guard('agent')->user()->roles;

            return $rol == 'Super Admin' || $rol == 'Agency Admin';
        });

        Blade::if('settings', function ($value) {
            $settings = SchoolHelper::settings();

            return collect($value)->map(function ($setting) use ($settings) {
                $setting = explode('.', $setting);

                return isset($settings[$setting[0]][$setting[1]]);
            });
        });

        Blade::directive('price', function ($price) {
            $settings = SchoolHelper::settings();
            return  $price. '' . $settings['school']['default_currency'];
        });

        Blade::directive('features', function ($features) {
            return "<?php if (\Auth::user()->hasRole('Super Admin') || (isset(Auth::user()->team->plan) && !empty(array_intersect(Auth::user()->team->plan->features, $features)))){ ?>";
        });

        Blade::directive('nofeatures', function ($features) {
            return '<?php } else { ?>';
        });

        Blade::directive('endfeatures', function ($features) {
            return '<?php } ?>';
        });
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        //
    }
}
