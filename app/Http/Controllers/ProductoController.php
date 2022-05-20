<?php

namespace App\Http\Controllers;

use App\Models\Producto;
use Illuminate\Http\Request;
use Illuminate\Database\Query\Builder;
use Illuminate\Support\Facades\DB;
use App\Models\Precio;
use App\Models\Marca;
use App\Models\Tienda;
use App\Models\PaginaExterna;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Lista_de_deseo;


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
        $marcas = Marca::all();
        $productos = $builder->simplePaginate(9);
        return view("producto.marcas", [
            "marcas" => $marcas,
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
            "productos" => Producto::all(),
            "categorias" => Categoria::all(),
            "subcategorias" => Subcategoria::all()
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
        $productos = Producto::orderBy("valoracion", 'asc')->get();

        return view("home", [
            "productos" => $productos
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
    public function show(Producto $producto, $id)
    {
        $precios = Precio::orderBy("precio", 'asc')->get();
        $precios = $precios->where("id_producto", $id);
        $listaPrecios = array();
        $listaId = [];
        foreach ($precios as $precio)
        {
            $listaPrecios[$precio->id] =$precio->precio;
        }
        $precioBarato = min( $listaPrecios);

        // Informacion del producto más barato
        $precio = Precio::all()->where('precio',$precioBarato)->first();
        $id_tiendaBarata = $precio->id_tienda;
        $tienda = Tienda::all()->where('id', $id_tiendaBarata)->first();
        $producto = Producto::all()->where("id",$id )->first();

        // TODOS 
        $tiendas = Tienda::all();
        $pagina_externa = PaginaExterna::all();
        $listaDeseo = Lista_de_deseo::all();
        return view("producto.show", [
            'producto' =>$producto,
            'precio' =>$precio,
            'precios'=>$precios,
            'tienda' =>$tienda,
            'tiendas' =>$tiendas,
            'pagina_e' =>$pagina_externa,
            'listaDeseo' => $listaDeseo
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
