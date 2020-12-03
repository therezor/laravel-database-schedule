# Laravel database schedule
Store your schedules in database (cache friendly)

## Installation

1) Run ```composer require therezor/laravel-database-schedule``` in your laravel project root folder

2) Run ```php artisan vendor:publish```

3) Apply migration ```php artisan migrate```

4) Use `TheRezor\DatabaseSchedule\Models\Schedule` to manage your database schedule

```php
<?php
    $schedule = new Schedule();
    $schedule->dailyAt('18:00');
    $schedule->command = MyComand::class;
    $schedule->params = ['id' => 1];
    $schedule->save();
}
?>
```
