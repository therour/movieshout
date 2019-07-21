<?php

namespace Tests\Unit;

use Tests\TestCase;
use App\Models\Showtime;
use Illuminate\Foundation\Testing\RefreshDatabase;
use App\Services\Cinema\Cgv\CgvShowtime;
use DateTime;
use Illuminate\Foundation\Testing\DatabaseTransactions;

class ExampleTest extends TestCase
{
    use DatabaseTransactions;

    /**
     * A basic test example.
     *
     * @return void
     */
    public function testBasicTest()
    {
        $showtime = new CgvShowtime('2190469', '2019-07-21', '10:00', '19021700', '017', []);
        $newShowtime = Showtime::updateOrCreate(
            ['provider_id' => $showtime->getId(), 'provider' => $showtime->getProvider()],
            [
                'showtime' => $showtime->getDateTime(),
                'movie_id' => $showtime->getMovieId(),
                'cinema_id' => $showtime->getCinemaId(),
                'properties' => $showtime->getProperties()
            ]
        );
        dd($newShowtime);
    }

    /** @test */
    public function it_coba()
    {
        $showtime = new CgvShowtime('2190469', '2019-07-21', '10:00', '19021700', '017', []);
        $newShowtime = Showtime::create([
            'provider_id' => $showtime->getId(),
            'provider' => $showtime->getProvider(),
            'showtime' => $showtime->getDateTime(),
            'movie_id' => $showtime->getMovieId(),
            'cinema_id' => $showtime->getCinemaId(),
            'properties' => $showtime->getProperties()
        ]);
        dd($newShowtime);
    }
}
