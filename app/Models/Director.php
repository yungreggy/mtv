<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Director extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function films()
    {
        return $this->hasMany(Film::class);
    }

    public function musicVideos()
    {
        return $this->hasMany(MusicVideo::class);
    }

    public function tvShowEpisodes()
    {
        return $this->hasMany(TvShowEpisode::class);
    }
}
