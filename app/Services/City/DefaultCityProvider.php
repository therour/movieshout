<?php

namespace App\Services\City;

class DefaultCityProvider implements CityProviderInterface
{
    /**
     * Database instance.
     *
     * @var \Illuminate\Database\Query\Builder
     */
    protected $db;

    /**
     * Cache instance.
     *
     * @var \Illuminate\Contracts\Cache\Repository
     */
    protected $cache;

    public function __construct($db, $cache)
    {
        $this->db = $db;
        $this->cache = $cache;
    }

    /**
     * list of cities.
     *
     * @return array
     */
    public function getCities()
    {
        return $this->cache->remember('cities', 3600, function () {
            return $this->db->table('cinemas')->distinct('city')->get(['city'])->pluck('city')->all();
        });
    }
}
