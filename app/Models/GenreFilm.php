<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GenreFilm extends Pivot
{
    public $incrementing = true;

    protected $table = 'genre_films';
    protected $fillable = ['film_id', 'genre_id'];
}
