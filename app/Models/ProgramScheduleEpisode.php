<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramScheduleEpisode extends Model
{
    use HasFactory;

    protected $fillable = [
        'program_schedule_id',
        'episode_id',
        'program_date_id'
    ];

    public function programSchedule()
    {
        return $this->belongsTo(ProgramSchedule::class);
    }

    public function episode()
    {
        return $this->belongsTo(TvShowsEpisode::class, 'episode_id');
    }

    public function programDate()
    {
        return $this->belongsTo(ProgramDate::class);
    }
}
