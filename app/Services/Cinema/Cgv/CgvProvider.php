<?php

namespace App\Services\Cinema\Cgv;

use DateTime;
use Goutte\Client as GoutteClient;
use Carbon\CarbonPeriod;
use Illuminate\Support\Arr;
use Symfony\Component\DomCrawler\Crawler;
use App\Services\Cinema\CinemaProviderInterface;
use App\Services\UrlParser;
use GuzzleHttp\Client as GuzzleHttpClient;
use DateTimeZone;

class CgvProvider implements CinemaProviderInterface
{
    use UrlParser;

    protected $client;

    protected $guzzleClient;

    const CGV_BASE_URL = 'https://www.cgv.id';
    const CGV_CINEMAS_URL = 'https://www.cgv.id/en/schedule/cinema';
    const CGV_MOVIES_URL = 'https://www.cgv.id/en/loader/home_movie_list';
    const CGV_SHOWTIMES_URL = 'https://www.cgv.id/en/schedule/cinema/{cinema_id}/{date}';

    public function __construct(GoutteClient $client, GuzzleHttpClient $guzzleClient)
    {
        $this->client = $client;
        $this->guzzleClient = $guzzleClient;

        CgvShowtime::setTimezone(new DateTimeZone('Asia/Jakarta'));
    }

    public function getProviderName()
    {
        return 'cgv';
    }

    public function fetchCinemas()
    {
        $crawler = $this->client->request('GET', self::CGV_CINEMAS_URL);

        $cinemas = $crawler->filter('.city')->each(function (Crawler $cityNode) {
            $city = $cityNode->filter('a')->eq(0)->text();
            $cinemas = $cityNode->filter('.area a')->each(function (Crawler $cinemasLinkNode) use ($city) {
                return new CgvCinema(
                    $cinemasLinkNode->attr('id'),
                    $cinemasLinkNode->text(),
                    $cinemasLinkNode->link()->getUri(),
                    $city
                );
            });
            return $cinemas;
        });

        return collect($cinemas)->flatten(1);
    }

    public function fetchMovies()
    {
        $client = $this->guzzleClient->get(self::CGV_MOVIES_URL);
        $json = json_decode((string) $client->getBody());

        /** @var \Symfony\Component\DomCrawler\Crawler $crawler */
        $crawler = tap(new Crawler())->add($json->now_playing);
        $movies = $crawler->filter('a')->each(function (Crawler $node) {
            $name = $this->client->request('GET', self::CGV_BASE_URL.$node->attr('href'))
                ->filter('.movie-info-title')->eq(0)->text();
            return new CgvMovie(
                Arr::get(explode('/', $node->attr('href')), 4),
                trim($name),
                ['image' => $node->filter('img')->eq(0)->attr('data-src')]
            );
        });

        return $movies;
    }

    public function fetchShowtimes($cinemaId, $date)
    {
        if ($date instanceof DateTime) {
            $date = $date->format('Y-m-d');
        }
        $showtimes = [];
        $crawler = $this->client->request('GET', $this->parseUrl(self::CGV_SHOWTIMES_URL, ['cinema_id' => $cinemaId, 'date' => $date]));
        $crawler->filter('.schedule-lists>ul>li')->each(function (Crawler $node) use (&$showtimes, $cinemaId, $date) {
            $a = $node->filter('.schedule-title>a')->eq(0);
            $movieId = Arr::get(explode('/', $a->attr('href')), 4);
            $node->filter('ul>li.schedule-type')->each(function (Crawler $node) use (&$showtimes, $movieId, $cinemaId, $date) {
                $audiName = $node->filter('.audi-nm')->eq(0)->text();
                $times = $node->siblings()->eq(0)->filter('ul>li')->extract(['_text']);
                $ids = $node->siblings()->eq(0)->filter('ul>li>a')->extract(['id']);
                foreach ($times as $i => $time) {
                    array_push($showtimes, new CgvShowtime($ids[$i], $date, trim($time), $movieId, $cinemaId, ['studio' => $audiName]));
                }
            });
        });

        return $showtimes;
    }
}
