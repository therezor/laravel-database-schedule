<?php

namespace TheRezor\DatabaseSchedule\Console;

use Illuminate\Console\Scheduling\ScheduleRunCommand as BaseScheduleCommand;
use TheRezor\DatabaseSchedule\Models\Schedule;
use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;

class ScheduleRunCommand extends BaseScheduleCommand
{
    public function handle()
    {
        $schedules = Config::get('database-schedule.cache.enabled') ? $this->getFromCache() : $this->getFromDatabase();

        foreach ($schedules as $s) {
            $this->execCommand($s);
        }

        parent::handle();
    }

    protected function execCommand($schedule) {
        $event = $this->schedule->command($schedule['command'], $schedule['params'])->cron($schedule['expression']);

        if ($schedule['even_in_maintenance_mode']) {
            $event->evenInMaintenanceMode();
        }

        if ($schedule['without_overlapping']) {
            $event->withoutOverlapping();
        }
    }

    protected function getFromCache()
    {
        $store = Config::get('database-schedule.cache.store', 'file');
        $key = Config::get('database-schedule.cache.key', 'database_schedule');

        return Cache::store($store)->rememberForever($key, function () {
            return $this->getFromDatabase();
        });
    }

    protected function getFromDatabase()
    {
        return Schedule::get()->toArray();
    }
}
