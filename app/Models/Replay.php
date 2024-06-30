<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Replay extends Model
{
    protected $table = 'replays';

    protected $fillable = [
        'program_schedule_id',
        'schedule_day_id',
        'description',
        'start_time',
        'end_time',
    ];

    public function programSchedule()
    {
        return $this->belongsTo(ProgramSchedule::class, 'program_schedule_id');
    }

    public function scheduleDay()
    {
        return $this->belongsTo(ScheduleDay::class, 'schedule_day_id');
    }
}
