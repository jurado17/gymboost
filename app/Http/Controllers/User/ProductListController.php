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
        $sort = $request->query('sort');

        return $this->filterProducts($categoryName, $brandId, $sort);
    }

    private function filterProducts($categoryName = null, $brandId = null, $sort = null)
    {
        $products = Product::where('published', 1)->with('promotion', 'category', 'brand', 'product_images');

        if ($categoryName) {
            $products = $products->whereHas('category', function($query) use ($categoryName) {
                $query->where('name', $categoryName);
            });
        }

        if ($brandId) {
            $products = $products->where('brand_id', $brandId);
        }

        // Apply sorting
        switch ($sort) {
            case 'price_asc':
                $products = $products->orderBy('price', 'asc');
                break;
            case 'price_desc':
                $products = $products->orderBy('price', 'desc');
                break;
            case 'newest':
           
                $products = $products->orderBy('created_at', 'desc');
                break;
        }

        $countProducts = $products->count();
        $categories = Category::get();
        $brands = Brand::get();

        $filterProducts = $products->paginate($countProducts)->withQueryString();

        return Inertia::render('User/ProductList', [
            'products' => ProductResource::collection($filterProducts),
            'categories' => $categories,
            'brands' => $brands
        ]);
    }
}
