<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class TvShowSchedule extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_id',
        'name',
        'description',
        'recurrence',
        'start_time',
        'end_time',
        'genre_id',
        'age_rating',
        'specific_date',
        'type',
        'start_year',
        'end_year',
    ];


    public function episode()
    {
        return $this->belongsTo(TvShowsEpisode::class, 'episode_id');
    }
    // Relation avec Program
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    // Relation avec TvShow
    public function tvShow()
    {
        return $this->belongsTo(TvShow::class);
    }

    // Relation avec Season
    public function season()
    {
        return $this->belongsTo(TvShowsSeason::class);
    }

    // Relation avec Genre
    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'tv_show_schedule_genres');
    }

    // Relation avec Tag
    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tv_show_schedule_tags');
    }

    // Relation avec DayOfWeek
    public function daysOfWeek()
    {
        return $this->belongsToMany(DayOfWeek::class, 'tv_show_schedule_days_of_week');
    }

    // Relation avec ProgramDate
    public function programDates()
    {
        return $this->belongsToMany(ProgramDate::class, 'tv_show_schedule_program_dates');
    }
}
