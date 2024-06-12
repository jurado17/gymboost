<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Category;
use Illuminate\Http\Request;
use Inertia\Inertia;

class CategoryController extends Controller
{
    public function index()
    {
        $categories = Category::get();
        return Inertia::render('Admin/Categories/Index', ['categories' => $categories]);
    }

    public function store(Request $request)
    {
        $category = new Category();

        $category->name = $request->name;
        $category->slug = $request->slug;


        $category->save();
        return redirect()->route('admin.categories.index')->with('success', 'category creado con exito');
    }

    public function update(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        $category->name = $request->name;
        $category->slug = $request->slug;
        
        $category->update();
        return redirect()->route('admin.categories.index')->with('success', 'category actualizado con exito');
    }

    public function delete($id)
    {
        $category = Category::findOrFail($id); // Corregido para buscar por ID solamente
        $category->delete(); // Eliminar el category encontrado
        return redirect()->route('admin.categories.index')->with('success', 'category eliminado con exito');
    }
}
