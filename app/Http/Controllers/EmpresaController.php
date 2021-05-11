<?php

namespace App\Http\Controllers;
use App\Models\empresa;
use App\Models\categoria;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use App\Models\Producto;
use App\Models\tipo;
class EmpresaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $tipos = tipo::all();
        $categorias = categoria::all();
        $empresas = empresa::all();
        return view('productos.empresa',compact('categorias','empresas','tipos'));

    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function empresa()
    {
        //
        $categorias = categoria::all();
        $creates = Producto::all();
        $empresas = empresa::all();
        $tipos = tipo::all();
        $empresas = empresa::all();
        return view('empresa-vista',compact('empresas','categorias','creates','tipos'));
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
        empresa::insert($datosproductos);

        return redirect('productos');


    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //EDITA EMPRESAAAAA

        $datosempresas=request()->except(['_token','_method']);

        if($request->hasFile('foto')){
        $empresas=empresa::findOrFail($id);
        Storage::delete('public/'.$empresas->foto);
         $datosempresas['foto']=$request->file('foto')->store('foto','public');
     }
 
        empresa::where('id','=',$id)->update($datosempresas);
        $empresas=empresa::findOrFail($id);
            return back();





    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
