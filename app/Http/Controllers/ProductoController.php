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
use App\Models\SubcategoriaTienda;
use App\Models\Rating;
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
        $estrellas = [];
        $busqueda = "";
        if ($request->get("search")) {
            $busqueda = $request->get("search");
        }
        # Exista o no exista búsqueda, los ordenamos
        $builder = Producto::orderBy("id");
        if ($busqueda) {
            # Si hay búsqueda, agregamos el filtro
            $builder->where("nombre", "LIKE", "%$busqueda%")
            ->orWhere("descripcion", "LIKE", "%$busqueda%");
        }
        # Al final de todo, invocamos a paginate que tendrá todos los filtros
        $marcas = Marca::all();
        $productos = $builder->simplePaginate(9);
        
        $producto = Producto::all()->where("id")->first();
        $ratings = Rating::all();
    
        return view("producto.marcas", [
            "marcas" => $marcas,
            "productos" => $productos,
    ],compact('ratings','producto'));


    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function categorias(Request $request)
    {
        $busq = "";
        if ($request->get("find")) {
            $busq = $request->get("find");
        }
        # Exista o no exista búsqueda, los ordenamos
        $builder = Producto::orderBy("id");
        if ($busq) {
            # Si hay búsqueda, agregamos el filtro
            $builder->where("nombre", "LIKE", "%$busq%")
            ->orWhere("descripcion", "LIKE", "%$busq%");
        }
        # Al final de todo, invocamos a paginate que tendrá todos los filtros
        $productos = $builder->simplePaginate(9);
        $producto = Producto::all()->where("id")->first();
        $ratings = Rating::all();

        return view("producto.categorias", [
            "productos" => $productos, 
            "categorias" => Categoria::all(),
            "subcategorias" => Subcategoria::all()
        ],compact('ratings','producto'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function top(Request $request)
    {
        $productos = Producto::orderBy("valoracion_media", 'desc')->where('valoracion_media', '>' ,3)->get();
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
        $precio = Precio::all()->where('precio',$precioBarato)->where("id_producto", $id)->first();
        $id_tiendaBarata = $precio->id_tienda;
        $tienda = Tienda::all()->where('id', $id_tiendaBarata)->first();
        $producto = Producto::all()->where("id",$id )->first();

        // TODOS 
        $tiendas = Tienda::all();
        $pagina_externa = PaginaExterna::all();
        /* Valoracion */ 
        
        $listaDeseo = Lista_de_deseo::all();
        if(\Auth::user()){
            $ratings = Rating::where('id_producto',$producto->id)->get();
            $rating_sum = Rating::where('id_producto',$producto->id)->sum('stars_rated');
            $user_rating = Rating::where('id_producto',$producto->id)->where('id_usuario', \Auth::user()->id)->first();

            
            if($ratings->count() > 0)
            {
                $rating_value = $rating_sum/$ratings->count();
                $producto->UpdateOrFail([
                    "valoracion_media" => $rating_value
                ]);
                
            } else {
                $rating_value = 0;
            }
            return view("producto.show", [
                'producto' =>$producto,
                'precio' =>$precio,
                'precios'=>$precios,
                'tienda' =>$tienda,
                'tiendas' =>$tiendas,
                'pagina_e' =>$pagina_externa,
                'listaDeseo' => $listaDeseo
            ], compact('ratings','rating_value','user_rating'));    
        } else{
            $ratings = Rating::where('id_producto',$producto->id)->get();
            $rating_sum = Rating::where('id_producto',$producto->id)->sum('stars_rated');

            if($ratings->count() > 0)
            {
                $rating_value = $rating_sum/$ratings->count();
                $producto->UpdateOrFail([
                    "valoracion_media" => $rating_value
                ]);
                
            } else {
                $rating_value = 0;
            }
            return view("producto.show", [
                'producto' =>$producto,
                'precio' =>$precio,
                'precios'=>$precios,
                'tienda' =>$tienda,
                'tiendas' =>$tiendas,
                'pagina_e' =>$pagina_externa,
                'listaDeseo' => $listaDeseo
            ],compact('ratings','rating_value'));
        }
        
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Producto  $producto
     * @return \Illuminate\Http\Response
     */
    public function Filtroid($id)
    {
        $marcas = Marca::all();
        $productos = Producto::where('id_marca', $id)->simplePaginate(9);
        return view("producto.marcas", [
            "marcas" => $marcas,
            "productos" => $productos,
    ]);
    }

    public function Subcategorias($id)
    {
        $categoria = Categoria::all();
        $subcategorias = Subcategoria::all();
        $prue = SubcategoriaTienda::all()->where('id_subcategoria','=',$id);
        foreach($prue as $p){
            if($p->id_tienda == 56){
                $product = Producto::where('id_subcategoria','=',$p->id)->simplePaginate(9);
                break;
            }
        }

        return view("producto.categorias", [
            "subcategorias" => $subcategorias,
            "categorias" => $categoria,
            "productos" => $product,
    ]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
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
