<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class MusicVideo extends Model
{
    use HasFactory;
    
    protected $fillable = ['title', 'album_id', 'year', 'director_id', 'duration', 'file_path', 'thumbnail_image', 'video_quality', 'age_rating', 'language', 'status', 'tags', 'play_frequency',  'release_date'];

    public function album()
    {
        return $this->belongsTo(Album::class);
    }

    public function director()
    {
        return $this->belongsTo(Director::class);
    }

    public function artist()
{
    return $this->belongsTo(Artist::class);
}
public function labels()
    {
        return $this->belongsToMany(Label::class, 'labels_music_videos');
    }


public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genres_musicvideos', 'musicvideo_id', 'genre_id');
    }

    public function programSchedules()
    {
        return $this->belongsToMany(ProgramSchedule::class, 'schedule_music_videos', 'music_video_id', 'schedule_id')
                    ->withPivot('play_time')
                    ->withTimestamps();
    }

    public function getDurationInSecondsAttribute()
    {
        $duration = $this->duration; // Assumant que la durée est stockée dans un format HH:MM:SS
        list($hours, $minutes, $seconds) = sscanf($duration, '%d:%d:%d');
        return $hours * 3600 + $minutes * 60 + $seconds;
    }


}
