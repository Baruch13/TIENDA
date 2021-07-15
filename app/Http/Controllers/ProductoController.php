<?php

namespace App\Http\Controllers;
use App\Models\Categoria;
use App\Models\Empresa;
use App\Models\Tipo;
use App\Models\footer;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;


class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
/*        $empresas = Empresa::all();
        $categorias = Categoria::all();
        $texto = $request->input('texto');
        $productos = Producto::query()
        ->where('descripcion', 'LIKE', "%{$texto}%")
        ->paginate(5);
        return view('productos.index', compact('productos','categorias','empresas'));


*/
        $categorias = Categoria::all();
        $empresas = Empresa::all();
        $tipos = Tipo::all();
        $footers = footer::all();
        $texto = $request->input('texto');
        $productos = Producto::query()
        ->where('id', 'LIKE', "%{$texto}%")
        ->orwhere('nombre', 'LIKE', "%{$texto}%")
        ->orwhere('descripcion', 'LIKE', "%{$texto}%")
        ->orwhere('fichatecnica', 'LIKE', "%{$texto}%")
        ->orwhere('ref', 'LIKE', "%{$texto}%")
        ->orderBy('id','desc')
        ->paginate(1);
        return view('productos.index', compact('productos','categorias','empresas','tipos','footers'));
   /*
        $datos['creates']=create::paginate(5);
        return view('productos.index',$datos);
        */

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $categorias = Categoria::all();
        $empresas = Empresa::all();
        $tipos = Tipo::all();
        return view('productos.create',compact('categorias','empresas','tipos'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        $datosproductos = request()->except('_token');
        if($request->hasFile('foto')){
            $datosproductos['foto']=$request->file('foto')->store('foto','public');
        }
        Producto::insert($datosproductos);

        return redirect('productos');

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\create  $create
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\create  $create
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        
        $productos=Producto::findOrFail($id);
        return view('productos.edit', compact('productos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\create  $create
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
       //EDITA PRODUCO

       $datosproductos =request()->except(['_token','_method']);

       if($request->hasFile('foto')){
       $productos=Producto::findOrFail($id);
       Storage::delete('public/'.$productos->foto);
        $datosproductos['foto']=$request->file('foto')->store('foto','public');
    }

       Producto::where('id','=',$id)->update($datosproductos);
       $productos=Producto::findOrFail($id);
        return view('productos.edit', compact('productos'));

      /*
       $datosproductos = request()->except('_token');

       $productos->nombre=$request->input('nombre');
       $productos->descripcion=$request->input('descripcion');

       $productos=create::findOrFail($id);
       $productos->save();
       return redirect()->route('productos.index');
*/
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\create  $create
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        $productos=Producto::findOrFail($id);

        if(Storage::delete('public/'.$productos->foto)){
            Producto::destroy($id);
         
        }
        
         return redirect('productos');
    }
}
