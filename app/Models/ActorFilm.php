<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Relations\Pivot;

class ActorFilm extends Pivot
{
    public $incrementing = true;

    protected $table = 'actor_film';
    protected $fillable = ['actor_id', 'film_id', 'role'];
}
