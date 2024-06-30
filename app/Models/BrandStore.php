<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BrandStore extends Model
{
    use HasFactory;

    protected $table = 'brands_stores'; // Spécifie le nom de la table
    protected $fillable = ['name', 'description', 'timestamps', 'logo_image'];



    public function pubs()
    {
        return $this->hasMany(Pub::class); // Assure-toi que la clé étrangère et les noms de table sont corrects
    }

    public function blocPubs()
    {
        return $this->hasMany(BlocPub::class); // Assure-toi que la clé étrangère et les noms de table sont corrects
    }
}




