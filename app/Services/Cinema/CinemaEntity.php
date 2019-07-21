<?php

namespace App\Services\Cinema;

abstract class CinemaEntity
{
    protected $id;
    protected $provider;
    protected $name;
    protected $link;
    protected $city;

    public function __construct($id, $provider, $name, $link, $city)
    {
        $this->id = $id;
        $this->provider = $provider;
        $this->name = $name;
        $this->link = $link;
        $this->city = $city;
    }

    public function getName()
    {
        return $this->name;
    }

    public function getProvider()
    {
        return $this->provider;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getLink()
    {
        return $this->link;
    }

    public function getCity()
    {
        return $this->city;
    }
}
