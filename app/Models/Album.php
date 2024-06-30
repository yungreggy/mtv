<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Album extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'year', 'label_id', 'artist_id', 'thumbnail_image', 'description', 'track_count', 'release_date', 'url'];

    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_albums');
    }
    
    public function musicVideos()
    {
        return $this->hasMany(MusicVideo::class);
    }

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function labels()
    {
        return $this->belongsToMany(Label::class, 'labels_albums');
    }
}

