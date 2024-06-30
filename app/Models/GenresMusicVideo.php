<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class GenreMusicVideo extends Pivot
{
    public $incrementing = true;

    protected $table = 'genres_musicvideos';
    protected $fillable = ['musicvideo_id', 'genre_id'];
}
