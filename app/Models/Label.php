<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Label extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'logo_image', 'website', 'description', 'foundation_year'];

    public function artist()
    {
        return $this->belongsTo(Artist::class);
    }

    public function albums()
    {
        return $this->belongsToMany(Album::class, 'labels_albums');
    }

    public function musicVideos()
    {
        return $this->belongsToMany(MusicVideo::class, 'labels_music_videos');
    }
}
