<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Genre extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description', 'icon_path', 'popularity', 'theme_color'];

    public function albums()
    {
        return $this->belongsToMany(Album::class, 'genre_albums', 'genre_id', 'album_id');
    }

    public function musicVideos()
    {
        return $this->belongsToMany(MusicVideo::class, 'genres_musicvideos', 'genre_id', 'musicvideo_id');
    }

    public function tvShows()
    {
        return $this->belongsToMany(TvShow::class, 'genre_tvshows', 'genre_id', 'tvshow_id');
    }
    public function films()
    {
        return $this->belongsToMany(Film::class, 'genre_films', 'genre_id', 'film_id');
    }
    public function artists()
    {
        return $this->belongsToMany(Artist::class, 'genre_artists', 'genre_id', 'artist_id');
    }


   
}

