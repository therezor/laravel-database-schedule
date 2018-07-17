<?php

return [
    /**
     *  Table and Model used for schedule list
     */
    'table' => 'schedule',
    'model' => \TheRezor\DatabaseSchedule\Models\Schedule::class,

    /**
     * Cache settings
     */
    'cache' => [
        'store'   => 'file',
        'key'     => 'database_schedule',
        'enabled' => true,
    ],
];
