<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ProductImage extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'image',
        'product_id',
        'created_at',
        'updated_at',
    ];

    function product(){
        return $this->belongsTo(Product::class);
    }
}
