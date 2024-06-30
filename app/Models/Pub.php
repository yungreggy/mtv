<?php

namespace App\Models;

use App\Events\PubUpdated;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Pub extends Model
{
    protected $fillable = [
        'name',
        'type',
        'year',
        'duration'
    ];

    protected static function boot()
    {
        parent::boot();

        static::updated(function ($pub) {
            event(new PubUpdated($pub));
        });
    }

    public function blocPubs()
    {
        return $this->belongsToMany(BlocPub::class, 'bloc_pub_pubs', 'pub_id', 'bloc_pub_id');
    }

    public function brandStore()
    {
        return $this->belongsTo(BrandStore::class, 'brand_store_id');
    }

    public function tags()
    {
        return $this->belongsToMany(Tag::class, 'tag_pubs');
    }

   
}
