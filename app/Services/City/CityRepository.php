<?php

namespace App\Services\City;

class CityRepository
{
    protected $provider;

    public function __construct(CityProviderInterface $provider)
    {
        $this->provider = $provider;
    }

    public function getCities()
    {
        return $this->provider->getCities();
    }
}
