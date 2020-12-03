<?php

namespace TheRezor\DatabaseSchedule\Scheduling;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Config;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Console\Scheduling\Schedule as BaseSchedule;

class Schedule extends BaseSchedule
{
    protected $isScheduleAdded = false;

    public function dueEvents($app)
    {
        if ($this->isScheduleAdded) {
            return parent::dueEvents($app);
        }

        $model = $app->make(Config::get('database-schedule.model'));
        $schedules = Config::get('database-schedule.cache.enabled') ? $this->getFromCache($model) : $model->all()->toArray();

        foreach ($schedules as $s) {
            $event = $this->command($s['command'], $s['params'])->cron($s['expression']);

            if ($s['even_in_maintenance_mode']) {
                $event->evenInMaintenanceMode();
            }

            if ($s['without_overlapping']) {
                $event->withoutOverlapping();
            }
        }

        return parent::dueEvents($app);
    }

    protected function getFromCache(Model $model)
    {
        $store = Config::get('database-schedule.cache.store', 'file');
        $key = Config::get('database-schedule.cache.key', 'database_schedule');

        return Cache::store($store)->rememberForever($key, function () use ($model) {
            return $model->all()->toArray();
        });
    }
}