<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;


class ProductoController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $si=Producto::all();
        // dd($si);
        //$prodc = Producto::paginate(10);
        //return view("producto.index", [
        //    "productos"->$prodc
        //]);
        //dd("hola");
        //$productos = Producto::paginate(4);
        //return view("producto.marcas", compact('productos'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function marcas(Request $request)
    {
        //if($request) {
        //    $query = trim($request->get('search'));
//
        //    $produc = Producto::Where('nombre','LIKE', '%'.$query.'%')
        //        ->orderBy('id','asc')
        //        ->paginate(9);
//
        //    return view("producto.marcas", [
        //        "productos" => $produc,$query
        //    ]);
        //}else{
        //    $all = Producto::all()->paginate(9);
//
        //    return view("productos.marcas", [
        //        "productos" => $all
        //    ]);
        //}

        $busqueda = "";
        if ($request->get("search")) {
            $busqueda = $request->get("search");
        }
        # Exista o no exista búsqueda, los ordenamos
        $builder = Producto::orderBy("id");
        if ($busqueda) {
            # Si hay búsqueda, agregamos el filtro
            $builder->where("nombre", "LIKE", "%$busqueda%")
            ->orWhere("descripcion", "LIKE", "%$busqueda%")
            ->orWhere("marca", "LIKE", "%$busqueda%")
            ->orWhere("precio", "LIKE", "$busqueda%");
        }
        # Al final de todo, invocamos a paginate que tendrá todos los filtros
        $productos = $builder->paginate(9);
        return view("producto.marcas", [
            "productos" => $productos,
    ]);


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function categorias(Request $request)
    {
        return view("producto.categorias", [
            "productos" => Producto::all()
        ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function top(Request $request)
    {
        return view("home", [
            "productos" => Producto::all()
        ]);
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function categoria(Request $request, $categoria)
    {
        return view("productos.categorias", [
            "productos" => Producto::all()
        ]);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function show(Producto $producto)
    {
        return view("productos.show", [
            'productos' =>$producto
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function edit(Producto $producto)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Producto $producto)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function destroy(Producto $producto)
    {
        //
    }
}
