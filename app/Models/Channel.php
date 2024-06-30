<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Channel extends Model
{
    use HasFactory;
    
    protected $fillable = ['name', 'description', 'thumbnail_image', 'logo', 'created_at', 'updated_at'];

    public function playlists()
    {
        return $this->hasMany(Playlist::class);
    }

    public function programs()
    {
        return $this->belongsToMany(Program::class, 'channel_program');
    }
}

