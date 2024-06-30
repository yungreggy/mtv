<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GenreAlbum extends Pivot
{
    public $incrementing = true;

    protected $table = 'genre_albums';
    protected $fillable = ['album_id', 'genre_id'];
}

