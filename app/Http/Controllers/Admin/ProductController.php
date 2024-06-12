<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Brand;
use App\Models\Category;
use App\Models\Product;
use App\Models\ProductImage;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Str;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::with('category', 'brand', 'product_images')->get();
        $brands = Brand::get();
        $categories = Category::get();
        return Inertia::render('Admin/Products/Index', ['products' => $products, 'brands' => $brands, 'categories' => $categories]);
    }

    public function store(Request $request)
    {
        $product = new Product;

        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;


        $product->save();

        //comprueba si el producto tiene imágenes subidas

        if ($request->hasFile('product_images')) {
            $productImage = $request->file('product_images');
            foreach ($productImage as $image) {
                //genera un nombre único para la imágen usando timestamps y strings randoms
                $uniqueName = time() .  '-' . Str::random(10) . '-' . $image->getClientOriginalExtension();
                //Alamcena la iamgen en la carpeta publiccon el nombre único
                $image->move(storage_path('product_images'), $uniqueName);

                //Crea una nueva imagen del producto con el product_id y el uniqueName
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }

        return redirect()->route('admin.products.index')->with('success', 'Producto creado con exito');
    }

    public function update(Request $request, $id)
    {
        $product = Product::findOrFail($id);

        $product->name = $request->name;
        $product->price = $request->price;
        $product->description = $request->description;
        $product->brand_id = $request->brand_id;
        $product->category_id = $request->category_id;

        if ($request->hasFile('product_images')) {
            $productImage = $request->file('product_images');
            foreach ($productImage as $image) {
                //genera un nombre único para la imágen usando timestamps y strings randoms
                $uniqueName = time() .  '-' . Str::random(10) . '-' . $image->getClientOriginalExtension();
                //Alamcena la iamgen en la carpeta publiccon el nombre único
                $image->move('product_images', $uniqueName);
                //Crea una nueva imagen del producto con el product_id y el uniqueName
                ProductImage::create([
                    'product_id' => $product->id,
                    'image' => 'product_images/' . $uniqueName,
                ]);
            }
        }

        $product->update();
        return redirect()->route('admin.products.index')->with('success', 'Producto actualizado con exito');
    }

    public function delete($id)
    {
        $product = Product::findOrFail($id); // Corregido para buscar por ID solamente
        $product->delete(); // Eliminar el producto encontrado
        return redirect()->route('admin.products.index')->with('success', 'Producto eliminado con exito');
    }

    public function deleteImage($id)
    {
        $image = ProductImage::where('id', $id)->delete();
        return redirect()->back()->with('success', 'Imagen eliminada con exito');
    }

    public function search(Request $request)
    {
        $query = $request->input('q');

        // Realiza la búsqueda en la base de datos
        $products = Product::where('name', 'like', '%' . $query . '%')
            ->orWhere('description', 'like', '%' . $query . '%')->with('product_images')
            ->get();

        return response()->json($products);
    }

    
}
