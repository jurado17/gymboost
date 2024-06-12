<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use Illuminate\Http\Request;
use Inertia\Inertia;

class BrandController extends Controller
{
    public function index()
    {
        $brands = Brand::get();
        return Inertia::render('Admin/Brands/Index', ['brands' => $brands]);
    }

    public function store(Request $request)
    {
        $brand = new Brand();

        $brand->name = $request->name;
        $brand->slug = $request->slug;


        $brand->save();
        return redirect()->route('admin.brands.index')->with('success', 'Brand creado con exito');
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $brand->name = $request->name;
        $brand->slug = $request->slug;
        
        $brand->update();
        return redirect()->route('admin.brands.index')->with('success', 'brand actualizado con exito');
    }

    public function delete($id)
    {
        $brand = Brand::findOrFail($id); // Corregido para buscar por ID solamente
        $brand->delete(); // Eliminar el brand encontrado
        return redirect()->route('admin.brands.index')->with('success', 'brand eliminado con exito');
    }
}
