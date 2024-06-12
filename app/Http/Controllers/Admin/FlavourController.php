<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Flavour;
use Illuminate\Http\Request;
use Inertia\Inertia;

class FlavourController extends Controller
{
    public function index(){
        $flavours = Flavour::get();

        return Inertia::render('Admin/Flavours/Index', ['flavours' => $flavours]);
    }

    public function store(Request $request){

        $flavour = new Flavour();
        $flavour->name = $request->name;
        $flavour->description  =$request->description;

        $flavour->save();

        return redirect()->route('admin.flavours.index')->with('success', 'Sabor creado con exito');
    }

    public function update(Request $request, $id){

        $flavour = Flavour::findOrFail($id);
        $flavour->name = $request->name;
        $flavour->description  =$request->description;

        $flavour->update();

        return redirect()->route('admin.flavours.index')->with('success', 'Sabor Actualizado con exito');
    }

    public function delete($id){
        $flavour = Flavour::findOrFail($id); // Corregido para buscar por ID solamente
        $flavour->delete(); // Eliminar el flavouro encontrado
        return redirect()->route('admin.flavours.index')->with('success', 'Sabor eliminado con exito');
    }
}
