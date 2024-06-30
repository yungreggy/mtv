<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ScheduleDay extends Model
{
    protected $fillable = ['program_schedule_id', 'day_of_week'];

    public function schedule()
    {
        return $this->belongsTo(ProgramSchedule::class, 'schedule_id');
    }

    public function replays()
    {
        return $this->hasMany(Replay::class, 'schedule_day_id');
    }

    public function programSchedule()
    {
        return $this->belongsTo(ProgramSchedule::class);
    }
}
