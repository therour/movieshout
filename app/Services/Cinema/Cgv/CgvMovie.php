<?php

namespace App\Services\Cinema\Cgv;

use App\Services\Cinema\MovieEntity;

class CgvMovie extends MovieEntity
{
    public function __construct($id, $name, $properties = [])
    {
        parent::__construct($id, $name, 'cgv', $properties);
    }
}
