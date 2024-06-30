<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Actor extends Model
{
    use HasFactory;

    protected $fillable = ['name'];

    public function films()
    {
        return $this->belongsToMany(Film::class, 'actor_film')->withPivot('role');
    }
}

