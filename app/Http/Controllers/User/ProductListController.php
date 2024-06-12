<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\Controller;
use App\Http\Resources\ProductResource;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductListController extends Controller
{
    public function index(Request $request)
    {
        $categoryName = $request->query('category');
        $brandId = $request->query('brand'); 

        return $this->filterProducts($categoryName, $brandId);
    }

    private function filterProducts($categoryName = null, $brandId = null)
    {
        $products = Product::with('promotion','category', 'brand', 'product_images');

        if ($categoryName) {
            $products = $products->whereHas('category', function($query) use ($categoryName) {
                $query->where('name', $categoryName);
            });
        }

        if ($brandId) {
            $products = $products->where('brand_id', $brandId);
        }

        $countProducts = $products->count();
        $categories = Category::get();
        $brands = Brand::get();

        $filterProducts = $products->filtered()->paginate($countProducts)->withQueryString();

        return Inertia::render('User/ProductList', [
            'products' => ProductResource::collection($filterProducts),
            'categories' => $categories,
            'brands' => $brands
        ]);
    }
}
