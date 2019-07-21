<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Services\Cinema\CinemaProvider;

class ShoutMoviesCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'shout:movies {--provider= : Set the cinema provider repository name}';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Get the movies lists.';

    /**
     * Cinema Provider instance.
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
        $this->provider->syncMovies();
    }
}
