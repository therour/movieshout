<?php

namespace App\Http\Controllers\Api;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Cinema;
use App\Http\Resources\ShowtimeResource;
use Illuminate\Support\Carbon;

class ShowtimeController extends Controller
{
    public function index(Cinema $cinema, Request $request)
    {
        $dates = $request->has('date') ? $request->get(['date']) : [Carbon::today()];

        $showtimes = $cinema->showtimes();
        foreach ($dates as $i => $date) {
            if ($i == 0) {
                $showtimes->whereDate('showtime', $date);
                continue;
            }
            $showtimes->orWhereDate('showtime', $date);
        }

        return ShowtimeResource::collection($showtimes->get());
    }
}
