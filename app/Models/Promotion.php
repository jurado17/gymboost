<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Promotion extends Model
{
    use HasFactory;

    protected $fillable = [
        'id',
        'type',
        'name',
        'description',
        'discount_code',
        'porcentual_discount',
        'active_until',
        'isActive'
    ];

    function product(){
        return $this->hasMany(Product::class);
    }
}
