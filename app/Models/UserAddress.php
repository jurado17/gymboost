<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserAddress extends Model
{
    use HasFactory;

    protected $fillable = [
        'type',
        'address1',
        'city',
        'province',
        'zip_code',
        'isMain',
        'user_id',
        'country_code',
    ];

    function user()
    {
        return $this->belongsTo(User::class);
    } 

    public function orders()
    {
        return $this->hasMany(Order::class);
    }
}
