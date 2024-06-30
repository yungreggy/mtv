<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvShow extends Model
{
    use HasFactory;

    protected $fillable = [
        'title',
        'years_active',
        'genre_id',
        'description',
        'poster',
        'creator',
        'season_count',
        'target_audience',
        'official_website',
        'status',
        'country_of_origin',
    ];

   // App\Models\TvShow.php
public function genres()
{
    return $this->belongsToMany(Genre::class, 'genre_tvshows', 'tvshow_id', 'genre_id');
}

public function tvShowSchedules()
{
    return $this->hasMany(TvShowSchedule::class);
}

public function seasons()
{
    return $this->hasMany(TvShowsSeason::class);
}
public function episodes()
    {
        return $this->hasManyThrough(TvShowsEpisode::class, TvShowsSeason::class, 'tv_show_id', 'season_id');
    }


}
