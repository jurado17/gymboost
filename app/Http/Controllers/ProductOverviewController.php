<?php

namespace App\Http\Controllers;

use App\Models\Flavour;
use App\Models\Product;
use App\Models\StockProduct;
use App\Models\Weight;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductOverviewController extends Controller
{
    public function index($slug)
    {
        $product = Product::where('slug', $slug)->with('product_images', 'brand', 'category', 'stock_products', 'promotion')->firstOrFail();
        
        $weights = Weight::get();
        $flavours = Flavour::get();
        $stockProduct = StockProduct::where('product_id', $product->id)->with('product.product_images', 'weight', 'flavour')->get();

        // Check if the product has an active promotion and calculate the discount price
        $discountedPrice = null;
        if ($product->promotion && $product->promotion->isActive && $product->promotion->active_until >= now()) {
            $discountedPrice = $product->price - ($product->price * $product->promotion->porcentual_discount);
            
        }

        return Inertia::render('User/ProductOverview', [
            'product' => $product,
            'weights' => $weights,
            'flavours' => $flavours,
            'stockProduct' => $stockProduct,
            'discountedPrice' => $discountedPrice
        ]);
    }
}
