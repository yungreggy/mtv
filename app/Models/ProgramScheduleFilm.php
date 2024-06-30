<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramScheduleFilm extends Model
{
    use HasFactory;

    protected $table = 'program_schedule_films';

    protected $fillable = ['program_schedule_id', 'film_id', 'genre_id', 'age_rating', 'program_date_id'];

    public function programSchedule()
    {
        return $this->belongsTo(ProgramSchedule::class);
    }

    public function film()
    {
        return $this->belongsTo(Film::class);
    }

    public function genre()
    {
        return $this->belongsTo(Genre::class);
    }

    public function programDate()
    {
        return $this->belongsTo(ProgramDate::class);
    }
}
