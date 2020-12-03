<?php

namespace TheRezor\DatabaseSchedule\Models;

use Illuminate\Console\Scheduling\ManagesFrequencies;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Config;

class Schedule extends Model
{
    use ManagesFrequencies;

    /**
     * The database table used by the model.
     *
     * @var string
     */
    protected $table;

    protected $attributes = [
        'expression' => '* * * * *',
        'params'     => '{}',
    ];

    protected $casts = [
        'params' => 'array',
    ];

    /**
     * Creates a new instance of the model.
     *
     * @param  array $attributes
     * @return void
     */
    public function __construct(array $attributes = [])
    {
        parent::__construct($attributes);

        $this->table = Config::get('database-schedule.table', 'schedule');
    }
}
