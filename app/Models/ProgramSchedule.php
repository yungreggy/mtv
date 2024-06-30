<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id', 'name', 'description', 'start_time', 'end_time', 'type',
        'status', 'priority', 'special_notes', 'repeat_schedule', 'frequency_id', 'continue_after_season'  
    ];

    protected $table = 'program_schedules';

    public function replays()
    {
        return $this->hasMany(Replay::class, 'program_schedule_id');
    }

    public function frequency()
    {
        return $this->belongsTo(Frequency::class, 'frequency_id');
    }

    public function program()
    {
        return $this->belongsTo(Program::class);
    }
    public function programDates()
    {
        return $this->hasMany(ProgramDate::class, 'program_id', 'program_id');
    }

    public function musicVideos()
    {
        return $this->belongsToMany(MusicVideo::class, 'schedule_music_videos', 'schedule_id', 'music_video_id')
                    ->withPivot('play_time', 'day_of_week')
                    ->withTimestamps();
    }

    public function days()
    {
        return $this->hasMany(ScheduleDay::class);
    }

    public function scheduleDays()
    {
        return $this->hasMany(ScheduleDay::class, 'program_schedule_id');
    }

    public function films()
    {
        return $this->belongsToMany(Film::class, 'program_schedule_films')
                    ->withPivot('genre_id', 'age_rating', 'program_date_id');
    }

    public function tvShows()
    {
        return $this->belongsToMany(TvShow::class, 'program_schedule_tv_show');
    }

    public function blocPubs()
    {
        return $this->belongsToMany(BlocPub::class, 'schedule_bloc_pubs', 'schedule_id', 'bloc_pub_id');
    }

    public function programDate()
    {
        return $this->belongsTo(ProgramDate::class);
    }

    public function getFilmDate($filmId)
    {
        return $this->dates()
                    ->whereHas('films', function ($query) use ($filmId) {
                        $query->where('films.id', $filmId);
                    })->first();
    }

    public function dates()
    {
        return $this->belongsToMany(ProgramDate::class, 'program_date_program_schedule')
                    ->withPivot('created_at', 'updated_at');
    }

    public function daysOfWeek()
    {
        return $this->belongsToMany(DayOfWeek::class, 'program_schedule_days_of_week', 'program_schedule_id', 'day_of_week_id');
    }

    public function tvShowSchedules()
    {
        return $this->hasMany(TvShowSchedule::class);
    }

    public function episodes()
    {
        return $this->hasManyThrough(
            TvShowsEpisode::class,
            ProgramScheduleEpisode::class,
            'program_schedule_id', // Foreign key on ProgramScheduleEpisode table
            'id', // Foreign key on TvShowsEpisode table
            'id', // Local key on ProgramSchedule table
            'episode_id' // Local key on ProgramScheduleEpisode table
        );
    }
    
}
