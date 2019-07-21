<?php

namespace App\Services\Cinema\Cgv;

use DateTime;
use App\Services\Cinema\ShowTimeEntity;
use DateTimeZone;

class CgvShowtime extends ShowTimeEntity
{
    protected static $timezone;

    public function __construct($id, $date, $time, $movieId, $cinemaId, $properties = [])
    {
        parent::__construct(
            $id,
            $this->createDateTime($date, $time),
            $movieId,
            $cinemaId,
            'cgv',
            $properties
        );
    }

    private function createDateTime($date, $time): DateTime
    {
        return DateTime::createFromFormat('Y-m-d H:i', $date.' '.$time, static::$timezone);
    }

    public static function setTimezone(DateTimeZone $timezone)
    {
        static::$timezone = $timezone;
    }
}
