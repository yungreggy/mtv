<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Film extends Model
{
    use HasFactory;

    protected $fillable = ['title', 'year', 'director_id', 'label_id', 'description', 'duration', 'file_path', 'local_image_path', 'url_poster', 'rating', 'primary_language', 'country_of_origin'];

    public function director()
    {
        return $this->belongsTo(Director::class);
    }

    public function label()
    {
        return $this->belongsTo(Label::class);
    }

    public function genres()
    {
        return $this->belongsToMany(Genre::class, 'genre_films', 'film_id', 'genre_id');
    }
    public function actors()
    {
        return $this->belongsToMany(Actor::class, 'actor_film')->withPivot('role');
    }

    public function programSchedules()
    {
        return $this->belongsToMany(ProgramSchedule::class, 'program_schedule_films', 'film_id', 'program_schedule_id')
                    ->withPivot('genre_id', 'age_rating')
                    ->withTimestamps();
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_films');
    }
    
}
