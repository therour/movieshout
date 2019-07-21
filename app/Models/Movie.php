<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;

class Movie extends Model
{
    use Compoships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['provider_id', 'provider', 'name', 'properties'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'array',
    ];

    public function showtimes()
    {
        return $this->hasMany(Showtime::class, ['movie_id', 'provider'], ['provider_id', 'provider']);
    }
}
