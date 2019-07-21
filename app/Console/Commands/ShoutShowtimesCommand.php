<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Cinema\CinemaProvider;
use Illuminate\Support\Carbon;

class ShoutShowtimesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shout:showtimes
                            {--date=today : Set the date}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sync showtimes';

    /**
     * Cinema provider instance.
     *
     * @var \App\Services\Cinema\CinemaProvider
     */
    protected $provider;

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct(CinemaProvider $provider)
    {
        parent::__construct();

        $this->provider = $provider;
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $date = Carbon::create($this->option('date'));

        $this->provider->syncShowtimes($date);
    }
}
