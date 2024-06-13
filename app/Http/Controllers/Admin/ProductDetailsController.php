<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flavour;
use App\Models\OrderItem;
use App\Models\Product;
use App\Models\StockProduct;
use App\Models\Weight;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductDetailsController extends Controller
{
    public function index($id)
    {   
        $product = Product::where('id',$id)->with('product.product_images', 'brand', 'category')->first();
        $stockProduct = StockProduct::where('product_id', $id)->with('product.product_images','product.brand', 'weight', 'flavour')->get();
        $weight = Weight::get();
        $flavours = Flavour::get();
        $orderItems = OrderItem::where('product_id', $id)->sum('quantity');

        return Inertia::render('Admin/Products/DetailsProduct', ['product' => $product,'stockProduct' => $stockProduct, 'weights' => $weight, 'flavours' => $flavours, 'orderItems' => $orderItems]);
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_id' => 'required',
            'weight_id' => 'required',
            'flavour_id' => 'required',
            'quantity' => 'required|numeric',
            
        ]);

        StockProduct::create($request->all());

        return redirect()->back()->with('success', 'Stock Product created successfully');
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'product_id' => 'required',
            'weight_id' => 'required',
            'flavour_id' => 'required',
            'quantity' => 'required|numeric',
        ]);

        StockProduct::find($id)->update($request->all());

        return redirect()->back()->with('success', 'Stock Product updated successfully');
    }

    public function delete($id)
    {
        $stockProduc = StockProduct::find($id);
        
        $stockProduc->delete();

        return redirect()->back()->with('success', 'Stock Product deleted successfully');
    }
}
