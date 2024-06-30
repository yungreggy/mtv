<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Playlist extends Model
{
    use HasFactory;
    
    protected $fillable = ['channel_id', 'name', 'description', 'thumbnail_image', 'playlist_type', 'visibility', 'created_at', 'updated_at', 'view_count'];

    public function musicVideos()
    {
        return $this->belongsToMany(MusicVideo::class, 'playlist_musicvideos');
    }
}

