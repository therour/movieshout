<?php

namespace App\Services\Cinema;

abstract class MovieEntity
{
    protected $name;
    protected $id;
    protected $provider;
    protected $properties;

    public function __construct($id, $name, $provider, $properties = [])
    {
        $this->id = $id;
        $this->name = $name;
        $this->provider = $provider;
        $this->properties = $properties;
    }

    public function getId()
    {
        return $this->id;
    }

    public function getName()
    {
        return $this->name;
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
