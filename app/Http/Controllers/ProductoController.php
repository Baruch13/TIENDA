<?php

namespace App\Http\Controllers;

use App\Models\Categoria;
use App\Models\Empresa;
use App\Models\footer;
use App\Models\Producto;
use App\Models\Bodega;
use App\Models\Tipo;
use Gloudemans\Shoppingcart\Facades\Cart;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class ProductoController extends Controller
{
    public function index(Request $request)
    {
        $categorias = Categoria::all();
        $empresas = Empresa::all();
        $tipos = Tipo::all();
        $footers = footer::all();
        $bodegas = Bodega::all();

        $texto = $request->input('texto');
        $productos = Producto::query()
            ->where('id', 'LIKE', "%{$texto}%")
            ->orwhere('nombre', 'LIKE', "%{$texto}%")
            ->orwhere('descripcion', 'LIKE', "%{$texto}%")
            ->orwhere('fichatecnica', 'LIKE', "%{$texto}%")
            ->orwhere('ref', 'LIKE', "%{$texto}%")
            ->orderBy('id', 'desc')
            ->paginate(6);

        return view('productos.index', compact('productos', 'categorias', 'empresas', 'tipos', 'footers', 'bodegas'));
    }

    public function create()
    {
        
        $categorias = Categoria::all();
        $empresas = Empresa::all();
        $tipos = Tipo::all();
        $footers = footer::all();

        return view('productos.create', compact('categorias', 'empresas', 'tipos', 'footers'));
    }
    public function store(Request $request)
    {
        $datosproductos = request()->except('_token');
        if ($request->hasFile('foto')) {
            $datosproductos['foto'] = $request->file('foto')->store('foto', 'public');
        }
        Producto::insert($datosproductos);

        return redirect('productos');
    }
 
    public function show(Producto $producto)
    {
    
    }

    public function edit($id)
    {
        $footers = footer::all();

        $empresas = Empresa::all();

        $productos = Producto::findOrFail($id);
        return view('productos.edit', compact('productos', 'empresas', 'footers'));
    }

    public function update(Request $request, $id)
    {
        $datosproductos = request()->except(['_token', '_method']);

        if ($request->hasFile('foto')) {
            $productos = Producto::findOrFail($id);
            Storage::delete('public/' . $productos->foto);
            $datosproductos['foto'] = $request->file('foto')->store('foto', 'public');
        }

        Producto::where('id', '=', $id)->update($datosproductos);
        $empresas = Empresa::all();
        $footers = footer::all();

        $productos = Producto::findOrFail($id);
        return view('productos.edit', compact('productos', 'empresas', 'footers'));
    }

    public function destroy($id)
    {
        $productos = Producto::findOrFail($id);

        if (Storage::delete('public/' . $productos->foto)) {
            Producto::destroy($id);
        }

        return redirect('productos');
    }
}
