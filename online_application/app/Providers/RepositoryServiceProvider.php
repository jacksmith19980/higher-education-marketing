<?php

namespace App\Providers;

use Illuminate\Support\ServiceProvider;

class RepositoryServiceProvider extends ServiceProvider
{
    /**
     * Bootstrap services.
     *
     * @return void
     */
    public function boot()
    {
        //
    }

    /**
     * Register services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(\App\Repositories\Interfaces\CampusRepositoryInterface::class, function () {
            return  new \App\Repositories\Cache\CachingCampusRepository(new \App\Repositories\CampusRepository());
        });

        $this->app->singleton(\App\Repositories\Interfaces\InstructorRepositoryInterface::class, function () {
            return  new \App\Repositories\Cache\CachingInstructorRepository(
                new \App\Repositories\InstructorRepository()
            );
        });

        $this->app->singleton(\App\Repositories\Interfaces\SettingRepositoryInterface::class, function () {
            return  new \App\Repositories\Cache\CachingSettingRepository(
                new \App\Repositories\SettingRepository()
            );
        });

        $this->app->singleton(\App\Repositories\Interfaces\SchoolRepositoryInterface::class, function () {
            return  new \App\Repositories\Cache\CachingSchoolRepository(
                new \App\Repositories\SchoolRepository()
            );
        });

        \App::bind(
            \App\Repositories\Interfaces\ClassroomRepositoryInterface::class,
            \App\Repositories\ClassroomRepository::class
        );
    }
}
