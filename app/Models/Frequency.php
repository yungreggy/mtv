<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Frequency extends Model
{
    protected $fillable = ['name', 'interval_unit', 'interval_value', 'start_date', 'end_date'];

   

    public function programSchedules()
    {
        return $this->hasMany(ProgramSchedule::class, 'frequency_id');
    }
}
