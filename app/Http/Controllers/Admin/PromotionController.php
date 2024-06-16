<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Promotion;
use Illuminate\Http\Request;
use Inertia\Inertia;

class PromotionController extends Controller
{
    public function index(){
        $promotions = Promotion::get();

        return Inertia::render('Admin/Promotions/Index', ['promotions' => $promotions]);
    }

    public function store(Request $request){
        $promotion = new Promotion();

        $promotion->name = $request->name;
        $promotion->type = $request->type;
        $promotion->description = $request->description;
        $promotion->discount_code = $request->discount_code;
        $promotion->porcentual_discount = $request->porcentual_discount;
        $promotion->active_until = $request->active_until;
        $promotion->save();

        return redirect()->route('admin.promotions.index')->with('success', 'Promoción creada con exito');
    }

    public function update(Request $request, $id){
        $promotion = Promotion::findOrFail($id);

        $promotion->name = $request->name;
        $promotion->type = $request->type;
        $promotion->description = $request->description;
        $promotion->discount_code = $request->discount_code;
        $promotion->porcentual_discount = $request->porcentual_discount;
        $promotion->active_until = $request->active_until;
        $promotion->update();

        return redirect()->route('admin.promotions.index')->with('success', 'Promoción Actualizada con exito');
    }

    public function delete($id){
        $promotion = Promotion::findOrFail($id);
        $promotion->delete();

        return redirect()->route('admin.promotions.index')->with('success', 'Promoción Eliminada con exito');
    }

    public function activePromotion($id){

        $promotion = Promotion::findOrFail($id);
        if ($promotion->isActive === 1) {
            $promotion->isActive = 0;
        } else {
            $promotion->isActive = 1;
        }
        $promotion->update();
        return redirect()->back()->with('success','Estado de la promocion cambiado');
    }

}
