<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tag extends Model
{
    protected $fillable = ['name'];

    public function films()
    {
        return $this->belongsToMany(Film::class, 'tag_films');
    }
    
    public function episodes()
{
    return $this->belongsToMany(TvShowsEpisode::class, 'tag_episodes', 'tag_id', 'episode_id')->withTimestamps();
}

public function pubs()
    {
        return $this->belongsToMany(Pub::class, 'tag_pubs');
    }


    public function musicVideos()
    {
        return $this->belongsToMany(MusicVideo::class, 'tag_music_videos');
    }

    // Ajoute les autres méthodes pour les relations avec TV shows, TV show episodes, etc.
}
