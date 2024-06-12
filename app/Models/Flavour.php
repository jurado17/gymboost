<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Spatie\Sluggable\HasSlug;
use Spatie\Sluggable\SlugOptions;

class Flavour extends Model
{
    use HasFactory;
    use HasSlug;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'description',
        'created_at', 
        'updated_at',
    ];
    
    public function getSlugOptions(): SlugOptions
    {
        return SlugOptions::create()
            ->generateSlugsFrom('name')
            ->saveSlugsTo('slug');
    }
}
