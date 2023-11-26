<?php

namespace App\Http;

use Illuminate\Foundation\Http\Kernel as HttpKernel;

class Kernel extends HttpKernel
{
    /**
     * The application's global HTTP middleware stack.
     *
     * These middleware are run during every request to your application.
     *
     * @var array
     */
    protected $middleware = [
        \App\Http\Middleware\PreventRequestsDuringMaintenance::class,
        \Illuminate\Foundation\Http\Middleware\ValidatePostSize::class,
        \App\Http\Middleware\TrimStrings::class,
        \Illuminate\Foundation\Http\Middleware\ConvertEmptyStringsToNull::class,
        \App\Http\Middleware\TrustProxies::class,
        \Fruitcake\Cors\HandleCors::class,
    ];

    /**
     * The application's route middleware groups.
     *
     * @var array
     */
    protected $middlewareGroups = [
        'web' => [
            \App\Http\Middleware\EncryptCookies::class,
            \Illuminate\Cookie\Middleware\AddQueuedCookiesToResponse::class,
            \Illuminate\Session\Middleware\StartSession::class,
            // \Illuminate\Session\Middleware\AuthenticateSession::class,
            \Illuminate\View\Middleware\ShareErrorsFromSession::class,
            \App\Http\Middleware\VerifyCsrfToken::class,
            //\Illuminate\Routing\Middleware\SubstituteBindings::class,
            //\App\Http\Middleware\Tenant\SwitchTenant::class,
        ],

        'tenant' => [
            \App\Http\Middleware\Tenant\SetTenant::class,
            \App\Http\Middleware\Tenant\SchoolAuth::class,
            \App\Http\Middleware\Tenant\Impersonate::class,
            /* \App\Http\Middleware\ApplicationAuth::class, */
            \App\Http\Middleware\Tenant\SetLocale::class,

            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],
        'api' => [
            \App\Http\Middleware\Tenant\SetTenantViaApi::class,
            /* \App\Http\Middleware\Tenant\SetTenant::class, */
            'throttle:api',
            \Illuminate\Routing\Middleware\SubstituteBindings::class,
        ],

        'webhook' => [
            \App\Http\Middleware\Tenant\Webhook::class,
        ],


    ];

    /**
     * The application's route middleware.
     *
     * These middleware may be assigned to groups or used individually.
     *
     * @var array
     */
    protected $routeMiddleware = [
        'auth' => \App\Http\Middleware\Authenticate::class,
        //'auth' => \App\Http\Middleware\Authenticate::class,
        'auth.basic' => \Illuminate\Auth\Middleware\AuthenticateWithBasicAuth::class,
        'bindings' => \Illuminate\Routing\Middleware\SubstituteBindings::class,
        'cache.headers' => \Illuminate\Http\Middleware\SetCacheHeaders::class,
        'can' => \Illuminate\Auth\Middleware\Authorize::class,
        'guest' => \App\Http\Middleware\RedirectIfAuthenticated::class,
        'signed' => \Illuminate\Routing\Middleware\ValidateSignature::class,
        'throttle' => \Illuminate\Routing\Middleware\ThrottleRequests::class,
        'schoolAuth' => \App\Http\Middleware\Tenant\SchoolAuth::class,
        'agentsAuth' => \App\Http\Middleware\AgentsAuth::class,
        'instructorsAuth' => \App\Http\Middleware\InstructorsAuth::class,
        'appAuth' => \App\Http\Middleware\ApplicationAuth::class,
        'parent' => \App\Http\Middleware\Tenant\ParentMiddleware::class,

        'activeAgency' => \App\Http\Middleware\Tenant\ActiveAgency::class,
        'plan.features' => \App\Http\Middleware\Tenant\PlanFeature::class,
    ];
    /**
     * The priority-sorted list of middleware.
     *
     * This forces non-global middleware to always be in the given order.
     *
     * @var array
     */
    protected $middlewarePriority = [
        \Illuminate\Session\Middleware\StartSession::class,
        \Illuminate\View\Middleware\ShareErrorsFromSession::class,
        \App\Http\Middleware\Authenticate::class,
        \Illuminate\Session\Middleware\AuthenticateSession::class,
        \Illuminate\Routing\Middleware\SubstituteBindings::class,
        \Illuminate\Auth\Middleware\Authorize::class,
    ];
}
