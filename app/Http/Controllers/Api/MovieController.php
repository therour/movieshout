<?php

namespace App\Http\Controllers\Api;

use App\Models\Movie;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\MovieResource;
use App\Models\Cinema;

class MovieController extends Controller
{
    public function index(Request $request)
    {
        $movies = Movie::query()
            ->when($request->has('city'), function ($query) use ($request) {
                $query->whereHas('showtimes', function ($showtimesQuery) use ($request) {
                    $showtimesQuery->whereIn(
                        'cinema_id',
                        Cinema::city($request->get('city'))->get(['provider_id'])->map->provider_id->toArray()
                    );
                });
            });

        $movies = $movies->get();
        return MovieResource::collection($movies);
    }
}
