<?php

namespace App\Services\Cinema;

use App\Models\Movie;
use App\Models\Cinema;
use App\Models\Showtime;
use Illuminate\Support\Arr;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Contracts\Container\Container;

class CinemaProvider
{
    /**
     * Laravel container instance.
     *
     * @var \Illuminate\Contracts\Container\Container
     */
    protected $app;

    /**
     * array of configs.
     *
     * @var array
     */
    protected $config;

    /**
     * List of registered cinema repository.
     *
     * @var array
     */
    protected $repositories = [];

    /**
     * Create a new cinemas provider instance.
     *
     * @param Container $app
     * @param array $config
     */
    public function __construct(Container $app, array $config = [])
    {
        $this->app = $app;
        $this->config = $config;
    }

    /**
     * register a cinema repository
     *
     * @param CinemaProviderInterface $repository
     * @return void
     */
    public function addRepository(CinemaProviderInterface $repository)
    {
        $this->repositories[$repository->getProviderName()] = $repository;
    }

    protected function fetchCinemas($providerName = null)
    {
        $repositories = $providerName !== null ? Arr::wrap($this->repositories[$providerName]) : $this->repositories;

        $cinemas = new Collection();
        /** @var CinemaProviderInterface $repository */
        foreach ($repositories as $repository) {
            foreach ($repository->fetchCinemas() as $cinema) {
                $cinemas->push($cinema);
            }
        }

        return $cinemas;
    }

    public function syncCinemas($providerName = null)
    {
        $cinemas = $this->fetchCinemas($providerName);

        return $cinemas->map(function (CinemaEntity $cinema) {
            return Cinema::updateOrCreate(
                ['provider' => $cinema->getProvider(), 'provider_id' => $cinema->getId()],
                ['name' => $cinema->getName(), 'link' => $cinema->getLink(), 'city' => $cinema->getCity()]
            );
        });
    }

    public function fetchMovies($providerName = null)
    {
        $repositories = $providerName !== null ? Arr::wrap($this->repositories[$providerName]) : $this->repositories;

        $movies = new Collection();
        /** @var CinemaProviderInterface $repository */
        foreach ($repositories as $repository) {
            foreach ($repository->fetchMovies() as $movie) {
                $movies->push($movie);
            }
        }

        return $movies;
    }

    public function syncMovies($providerName = null)
    {
        $movies = $this->fetchMovies($providerName);

        return $movies->map(function (MovieEntity $movie) {
            return Movie::updateOrCreate(
                ['provider_id' => $movie->getId(), 'provider' => $movie->getProvider()],
                ['name' => $movie->getName(), 'properties' => $movie->getProperties()]
            );
        });
    }

    public function fetchShowtimes($date, $cinemaId = null, $providerName = null)
    {
        $showtimes = new Collection();
        $repositories = !is_null($providerName) ? Arr::wrap($this->repositories[$providerName]) : $this->repositories;
        if (empty($repositories)) {
            return;
        }

        /** @var CinemaProviderInterface $repository */
        foreach ($repositories as $repository) {
            $cinemas = Cinema::provider($repository->getProviderName())
                ->when(! is_null($cinemaId), function ($query) use ($cinemaId) {
                    return $query->where('provider_id', $cinemaId)->limit(1);
                })
                ->get();
            foreach ($cinemas as $cinema) {
                foreach ($repository->fetchShowtimes($cinema->provider_id, $date) as $showtime) {
                    $showtimes->push($showtime);
                }
            }
        }

        return $showtimes;
    }

    public function syncShowtimes($date, $cinemaId = null, $providerName = null)
    {
        $showtimes = $this->fetchShowtimes($date, $cinemaId, $providerName);

        return $showtimes->map(function (ShowTimeEntity $showtime) {
            return Showtime::updateOrCreate(
                ['provider_id' => $showtime->getId(), 'provider' => $showtime->getProvider()],
                [
                    'showtime' => $showtime->getDateTime(),
                    'movie_id' => $showtime->getMovieId(),
                    'cinema_id' => $showtime->getCinemaId(),
                    'properties' => $showtime->getProperties(),
                ]
            );
        });
    }
}
