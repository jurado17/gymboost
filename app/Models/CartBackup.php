<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartBackup extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'cart_data',
    
    ];

    protected $casts = [
        'cart_data' => 'array',
    ];
}
