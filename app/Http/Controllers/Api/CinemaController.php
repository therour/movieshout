<?php

namespace App\Http\Controllers\Api;

use App\Models\Cinema;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Http\Resources\CinemaResource;

class CinemaController extends Controller
{
    public function index(Request $request)
    {
        $cinemas = Cinema::query()
            ->when($request->has('city'), function ($query) use ($request) {
                $query->where('city', $request->get('city'));
            })
            ->get();

        return CinemaResource::collection($cinemas);
    }
}
