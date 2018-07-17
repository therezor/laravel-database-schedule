<?php

namespace TheRezor\DatabaseSchedule;

use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Support\Facades\Config;
use TheRezor\DatabaseSchedule\Observer\ScheduleObserver;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $this->publishes([
            __DIR__ . '/../config/database-schedule.php' => config_path('database-schedule.php'),
        ], 'config');

        $this->publishes([
            __DIR__ . '/../migrations/' => database_path('migrations'),
        ], 'migrations');

        if (Config::get('database-schedule.cache.enabled')) {
            $model = Config::get('database-schedule.model');
            $model::observe(ScheduleObserver::class);
        }
    }

    /**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
    }
}
