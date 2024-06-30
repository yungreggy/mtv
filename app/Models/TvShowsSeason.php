<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvShowsSeason extends Model
{
    use HasFactory;

    protected $fillable = [
        'tv_show_id',
        'season_number',
        'year',
        'start_date',
        'end_date',
        'episode_count',
        'description',
        'thumbnail_image',
        'streaming_url',
    ];

    // Relation avec les épisodes
    public function episodes()
    {
        return $this->hasMany(TvShowsEpisode::class, 'season_id');
    }

    public function tvShowSchedules()
    {
        return $this->hasMany(TvShowSchedule::class);
    }

    // Relation avec la série
    public function tvShow()
    {
        return $this->belongsTo(TvShow::class, 'tv_show_id');
    }
    

}
