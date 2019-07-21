<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

class Cinema extends Model
{
    use Compoships;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['name', 'provider', 'provider_id', 'link', 'city'];

    public function scopeProvider(Builder $query, $name)
    {
        return $query->where('provider', $name);
    }

    public function scopeCity(Builder $query, $city)
    {
        return $query->where('city', $city);
    }

    public function showtimes()
    {
        return $this->hasMany(Showtime::class, ['cinema_id', 'provider'], ['provider_id', 'provider']);
    }
}
