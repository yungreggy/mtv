<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Artist extends Model
{
    protected $fillable = [
        'name', 'biography', 'website', 'main_genre', 'career_start_year', 'country_of_origin', 'thumbnail_image'
    ];

    public function albums()
    {
        return $this->hasMany(Album::class);
    }

    public function musicVideos()
    {
        return $this->hasMany(MusicVideo::class);
    }

    public function getRouteKeyName()
    {
        return 'name';
    }

    public function genres()
{
    return $this->belongsToMany(Genre::class, 'genre_artists');
}

public function genre()
{
    return $this->belongsTo(Genre::class, 'main_genre');
}
    
}

