<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;


use App\Models\CodeHolder;
class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        Commands\Inspire::class,
        Commands\CommandStatus::class,
        Commands\CreateContestHolder::class,
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command('inspire')->hourly();
        $schedule->command('crsh:status')->everyMinute();

        $schedule->command('crsh:create_holder')->everyFiveMinutes()->when(function () {
            $contestHolders = CodeHolder::liveOrUpcoming();
            return count($contestHolders) == 0 ? true : false;
        });
    }
}