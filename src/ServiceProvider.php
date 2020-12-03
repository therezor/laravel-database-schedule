<?php

namespace TheRezor\DatabaseSchedule;

use Illuminate\Support\Facades\App;
use Illuminate\Support\ServiceProvider as LaravelServiceProvider;
use Illuminate\Support\Facades\Config;
use TheRezor\DatabaseSchedule\Observer\ScheduleObserver;
use Illuminate\Console\Scheduling\Schedule as BaseSchedule;
use TheRezor\DatabaseSchedule\Scheduling\Schedule;

class ServiceProvider extends LaravelServiceProvider
{
    /**
     * Bootstrap any application services.
     *
     * @return void
     */
    public function boot()
    {
        $configPath = __DIR__.'/../config/database-schedule.php';
        $this->publishes([
            $configPath => App::configPath('database-schedule.php'),
        ], 'config');
        $this->mergeConfigFrom($configPath, 'database-schedule');

        $this->publishes([
            __DIR__.'/../migrations/' => App::databasePath('migrations'),
        ], 'migrations');

        $config = $this->app['config'];

        if ($config->get('database-schedule.cache.enabled')) {
            $model = $config->get('database-schedule.model');
            $model::observe(ScheduleObserver::class);
        }

        $this->app->extend(BaseSchedule::class, function () use ($config) {
            return (new Schedule($this->scheduleTimezone($config)))
                ->useCache($this->scheduleCache());
        });
    }

    protected function scheduleTimezone($config)
    {
        return $config->get('app.schedule_timezone', $config->get('app.timezone'));
    }

    protected function scheduleCache()
    {
        return $_ENV['SCHEDULE_CACHE_DRIVER'] ?? null;
    }
}
