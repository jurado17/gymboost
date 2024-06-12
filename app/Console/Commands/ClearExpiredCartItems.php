<?php
namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\CartItem;
use App\Models\StockProduct;
use Carbon\Carbon;

class ClearExpiredCartItems extends Command
{
    protected $signature = 'cart:clear-expired';
    protected $description = 'Clear expired cart items and restore stock';

    public function __construct()
    {
        parent::__construct();
    }

    public function handle()
    {
        $expiredCartItems = CartItem::where('reserved_until', '<', Carbon::now())->get();
        
        foreach ($expiredCartItems as $cartItem) {
            
            $stockProduct = StockProduct::where([
                'product_id' => $cartItem->product_id,
                'weight_id' => $cartItem->weight_id,
                'flavour_id' => $cartItem->flavour_id,
            ])->first();

            if ($stockProduct) {
                $stockProduct->increment('quantity', $cartItem->quantity);
            }

            // Delete cart item
            $cartItem->delete();
        }

        

        $this->info('El tiempo de resrva del carrito ha expirado.Ta bien');
    }
}
