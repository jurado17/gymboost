<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Weight;
use Illuminate\Http\Request;
use Inertia\Inertia;

class WeightController extends Controller
{
    public function index(){
        $weights = Weight::get();

        return Inertia::render('Admin/Weights/Index', ['weights' => $weights]);
    }

    public function store(Request $request){
        $weights = new Weight();

        $weights->name = $request->name;
        $weights->weight_g = $request->weight_g;
        $weights->price = $request->price;
        $weights->save();

        return redirect()->route('admin.weights.index')->with('success', 'Tamaño creado con exito');
    }

    public function update(Request $request, $id){
        $weights = Weight::FindOrFail($id);
        $weights->name = $request->name;
        $weights->weight_g = $request->weight_g;
        $weights->price = $request->price;
        $weights->update();

        return redirect()->route('admin.weights.index')->with('success', 'Tamaño actualizado con exito');
    }

    public function delete($id){
        $weights = Weight::FindOrFail($id);
        $weights->delete();
        return redirect()->route('admin.weights.index')->with('success', 'Tamaño Eliminado con exito');
    }
}
