<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvShowsEpisode extends Model
{
    use HasFactory;

    protected $fillable = [
        'season_id',
        'episode_number',
        'title',
        'air_date',
        'description',
        'duration',
    ];

    // Relation avec la saison
    public function tvShow()
    {
        return $this->belongsTo(TvShow::class, 'tv_show_id');
    }
    
    public function season()
    {
        return $this->belongsTo(TvShowsSeason::class, 'season_id');
    }
    

    public function programScheduleTvShows()
    {
        return $this->hasMany(TvShowSchedule::class);
    }
    public function schedules()
    {
        return $this->belongsToMany(ProgramSchedule::class, 'program_schedule_episodes', 'episode_id', 'program_schedule_id')
                    ->withPivot('program_date_id');
    }

    public function tags()
{
    return $this->belongsToMany(Tag::class, 'tag_episodes', 'episode_id', 'tag_id')->withTimestamps();
}

    
    
}

