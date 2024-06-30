<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Bumper extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'year', 'duration', 'thumbnail_image', 'file_path'];
}
