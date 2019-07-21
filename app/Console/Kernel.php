<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;
use App\Console\Commands\ShoutCinemasCommand;
use App\Console\Commands\ShoutMoviesCommand;
use App\Console\Commands\ShoutShowtimesCommand;
use Illuminate\Support\Carbon;

class Kernel extends ConsoleKernel
{
    /**
     * The Artisan commands provided by your application.
     *
     * @var array
     */
    protected $commands = [
        //
    ];

    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        $schedule->command(ShoutCinemasCommand::class)->dailyAt('03:00');
        $schedule->command(ShoutMoviesCommand::class)->dailyAt('03:05');
        $schedule->command(ShoutShowtimesCommand::class, ['--date' => Carbon::today()])->dailyAt('03:15');
        $schedule->command(ShoutShowtimesCommand::class, ['--date' => Carbon::today()->addDays(1)])->dailyAt('03:35');
        $schedule->command(ShoutShowtimesCommand::class, ['--date' => Carbon::today()->addDays(2)])->dailyAt('03:55');
        $schedule->command(ShoutShowtimesCommand::class, ['--date' => Carbon::today()->addDays(3)])->dailyAt('03:15');
        $schedule->command(ShoutShowtimesCommand::class, ['--date' => Carbon::today()->addDays(4)])->dailyAt('03:35');
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
