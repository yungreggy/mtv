<?php


namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

// BlocPub.php
class BlocPub extends Model
{
    protected $fillable = [
        'name',
        'program_id',
        'include_intro',
        'include_outro',
        'number_of_pubs',
        'ad_types',
        'start_year',
        'end_year',
        'duration',
      
    ];

    public function pubs()
    {
        return $this->belongsToMany(Pub::class, 'bloc_pub_pubs')
                    ->withPivot('order')
                    ->orderBy('pivot_order');
    }
    
    



    public function program()
    {
        return $this->belongsTo(Program::class);
    }

 
}



