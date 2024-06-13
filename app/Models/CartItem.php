<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CartItem extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'product_id',
        'weight_id',
        'flavour_id',
        'quantity',
        'final_price',
        'reserved_until',
        'promotion_id'
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    // Relaciones
    public function product()
    {
        return $this->belongsTo(Product::class);
    }

    public function weight()
    {
        return $this->belongsTo(Weight::class);
    }

    public function flavour()
    {
        return $this->belongsTo(Flavour::class);
    }

    public function stock_item()
    {
        return $this->belongsTo(StockProduct::class, 'product_id');
    }
}
