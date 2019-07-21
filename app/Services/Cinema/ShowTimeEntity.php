<?php

namespace App\Services\Cinema;

use DateTime;

abstract class ShowTimeEntity
{
    protected $id;
    protected $datetime;
    protected $movieId;
    protected $cinemaId;
    protected $provider;
    protected $properties;

    public function __construct($id, DateTime $datetime, $movieId, $cinemaId, $provider, $properties = [])
    {
        $this->id = $id;
        $this->datetime = $datetime;
        $this->movieId = $movieId;
        $this->cinemaId = $cinemaId;
        $this->provider = $provider;
        $this->properties = $properties;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getDateTime(): DateTime
    {
        return $this->datetime;
    }

    public function getMovieId()
    {
        return $this->movieId;
    }

    public function getCinemaId()
    {
        return $this->cinemaId;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getProperties()
    {
        return $this->properties;
    }
}
