<?php

namespace App\Providers;

use App\Services\Cinema\CinemaProvider;
use Illuminate\Support\ServiceProvider;
use App\Services\Cinema\Cgv\CgvProvider;
use App\Services\City\CityRepository;
use App\Services\City\DefaultCityProvider;
use Illuminate\Support\Facades\Schema;

class AppServiceProvider extends ServiceProvider
{
    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton(CinemaProvider::class, function ($app, $config) {
            return tap(new CinemaProvider($app, $config), function ($provider) use ($app) {
                $provider->addRepository($app->make(CgvProvider::class));
            });
        });
        $this->app->singleton(CityRepository::class, function ($app) {
            return new CityRepository(
                new DefaultCityProvider($app['db'], $app['cache'])
            );
        });
    }

    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        Schema::defaultStringLength(225);
    }
}
