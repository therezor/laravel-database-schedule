<?php

namespace TheRezor\DatabaseSchedule\Observer;

use Illuminate\Support\Facades\Config;
use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class ScheduleObserver
{
    public function saved(Model $model)
    {
        $store = Config::get('database-schedule.cache.store', 'file');
        $key = Config::get('database-schedule.cache.key', 'database_schedule');

        Cache::store($store)->forget($key);
    }
}
