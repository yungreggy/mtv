<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PlaylistMusicVideo extends Model
{
    use HasFactory;
    
    protected $table = 'playlist_musicvideos';
    protected $fillable = ['playlist_id', 'musicvideo_id'];

    public function playlist()
    {
        return $this->belongsTo(Playlist::class);
    }

    public function musicVideo()
    {
        return $this->belongsTo(MusicVideo::class);
    }
}

