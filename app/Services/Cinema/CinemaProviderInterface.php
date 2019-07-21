<?php

namespace App\Services\Cinema;

interface CinemaProviderInterface
{
    public function getProviderName();

    public function fetchCinemas();

    public function fetchMovies();

    public function fetchShowtimes($cinemaId, $date);
}
