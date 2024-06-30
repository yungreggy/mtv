<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class DayOfWeek extends Model
{
    use HasFactory;

    protected $table = 'days_of_week';

    protected $fillable = ['name'];
    public function programSchedules()
    {
        return $this->belongsToMany(ProgramSchedule::class, 'program_schedule_days_of_week', 'day_of_week_id', 'program_schedule_id');
    }
    
}
