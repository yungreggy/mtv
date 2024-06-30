<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Program extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description', 'duration', 'genre', 'status', 'target_audience', 'thumbnail_image', 'premiere_date', 'timestamps'];

    public function schedules()
    {
        return $this->hasMany(ProgramSchedule::class);
    }

    public function adBlocks()
    {
        return $this->hasMany(BlocPub::class);
    }

    public function channels()
    {
        return $this->belongsToMany(Channel::class, 'channel_program');
    }

    public function dates()
    {
        return $this->hasMany(ProgramDate::class);
    }

    public function episodes()
{
    return $this->hasManyThrough(
        TvShowsEpisode::class,
        ProgramScheduleEpisode::class,
        'program_schedule_id',  // Foreign key on ProgramScheduleEpisode table
        'id',                   // Foreign key on TvShowsEpisode table
        'id',                   // Local key on ProgramSchedule table
        'episode_id'            // Local key on ProgramScheduleEpisode table
    );
}
    
}

