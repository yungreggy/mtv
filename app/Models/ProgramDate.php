<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProgramDate extends Model
{
    use HasFactory;

    protected $fillable = ['program_id', 'date'];

    /**
     * Get the program that owns the date.
     */
    public function program()
    {
        return $this->belongsTo(Program::class);
    }

    public function schedules()
    {
        return $this->belongsToMany(ProgramSchedule::class, 'program_date_program_schedule')
                    ->withPivot('created_at', 'updated_at');
    }
    public function tvShowSchedules()
    {
        return $this->hasMany(TvShowSchedule::class);
    }
}
