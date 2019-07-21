<?php

namespace App\Services\Cinema\Cgv;

use App\Services\Cinema\CinemaEntity;

class CgvCinema extends CinemaEntity
{
    public function __construct($id, $name, $link, $city)
    {
        parent::__construct($id, 'cgv', $name, $link, $city);
    }
}
