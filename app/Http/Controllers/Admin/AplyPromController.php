<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Product;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AplyPromController extends Controller
{
    public function index(){
        $aplyPromProduct = Product::whereNotNull('promotion_id')->with('promotion', 'brand', 'category', 'product_images')->get();

        $nonAplyPromProduct = Product::whereNull('promotion_id')->with('promotion', 'brand', 'category', 'product_images')->get();

        $promotions = Promotion::where('type','producto')->get();

        return Inertia::render('Admin/Promotions/AplyProms', ['aplyPromProduct' => $aplyPromProduct, 'nonAplyPromProduct'=> $nonAplyPromProduct, 'promotions'=> $promotions]);
    }

    public function aplyPromotion(Request $request){
        $idProduct = $request->product_id;

        $product = Product::where('id',$idProduct)->first();

        $product->promotion_id = $request->promotion_id;
        $product->save();
        
        return redirect()->back()->with('success','Promocion aplicada con exito');
    }

    public function update(Request $request){
        $productIds = $request->product_ids;
        $promotionId = $request->promotion_id;
    
        Product::whereIn('id', $productIds)->update(['promotion_id' => $promotionId]);
    
        return redirect()->back()->with('success', 'Promociones actualizadas con éxito');
    }
    

    public function delete(Request $request) {
        $productIds = $request->input('product_ids', []);
    
        if (count($productIds) > 0) {
            Product::whereIn('id', $productIds)->update(['promotion_id' => null]);
            return response()->json(['success' => 'Promociones eliminadas con éxito']);
        } else {
            return response()->json(['error' => 'No se proporcionaron IDs de productos'], 400);
        }
    }
    
    
}
