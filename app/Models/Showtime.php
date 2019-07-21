<?php

namespace App\Models;

use Awobaz\Compoships\Compoships;
use Illuminate\Database\Eloquent\Model;
use CoenJacobs\EloquentCompositePrimaryKeys\HasCompositePrimaryKey;

class Showtime extends Model
{
    use Compoships, HasCompositePrimaryKey;

    /**
     * The primary key associated with the table.
     *
     * @var string
     */
    protected $primaryKey = ['provider', 'provider_id'];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['provider_id', 'showtime', 'movie_id', 'cinema_id', 'provider', 'properties'];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'properties' => 'array',
    ];

    /**
     * The attributes that should be mutated to dates.
     *
     * @var array
     */
    protected $dates = ['created_at', 'updated_at', 'showtime'];

    public function movie()
    {
        return $this->belongsTo(Movie::class, ['movie_id', 'provider'], ['provider_id', 'provider']);
    }

    public function cinema()
    {
        return $this->belongsTo(Cinema::class, ['cinema_id', 'provider'], ['provider_id', 'provider']);
    }
}
