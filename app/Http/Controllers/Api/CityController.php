<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Services\City\CityRepository;

class CityController extends Controller
{
    public function index(CityRepository $cityRepository)
    {
        return $cityRepository->getCities();
    }
}
