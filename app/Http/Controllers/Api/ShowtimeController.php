<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Http\Resources\ShowtimeResource;
use Illuminate\Support\Carbon;
use App\Models\Showtime;
use App\Models\Movie;
use Illuminate\Support\Str;

class ShowtimeController extends Controller
{
    public function index(Request $request)
    {
        $request->validate([
            'cinema' => 'sometimes|exists:cinemas,id',
            'movie' => 'sometimes|exists:movies,id',
            'date' => 'sometimes|date',
            'city' => 'sometimes',
            'group_by' => 'sometimes|string'
        ]);

        $showtimes = $this->createShowtimeQuery($request)->get();
        return ShowtimeResource::collection($showtimes);
    }

    protected function createShowtimeQuery(Request $request)
    {
        $date = $request->has('date') ? $request->get('date') : Carbon::today();

        return Showtime::query()
            ->join('cinemas', function ($join) {
                $join->on('cinema_id', '=', 'cinemas.provider_id');
                $join->on('showtimes.provider', '=', 'cinemas.provider');
            })
            ->addSelect(['showtimes.*', 'cinemas.city', 'cinemas.name as cinema_name'])
            ->whereDate('showtime', $date)
            ->when($request->has('cinema'), function ($query) use ($request) {
                $cinema = Cinema::find($request->get('cinema'));
                $query->where('cinema_id', $cinema->provider_id)->where('showtimes.provider', $cinema->provider);
            })
            ->when($request->has('movie'), function ($query) use ($request) {
                $movie = Movie::find($request->get('movie'));
                $query->where('movie_id', $movie->provider_id)->where('showtimes.provider', $movie->provider);
            })
            ->when($request->has('city'), function ($query) use ($request) {
                $query->whereHas('cinema', function ($cinemaQuery) use ($request) {
                    $cinemaQuery->where('city', $request->get('city'));
                });
            });
    }
}
