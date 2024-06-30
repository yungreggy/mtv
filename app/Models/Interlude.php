<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Interlude extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'year', 'description', 'duration', 'thumbnail_image', 'file_path'];
}

