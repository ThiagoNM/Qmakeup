<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\CategoriaScraping;
use Illuminate\Http\Request;
use App\Models\CategoriaTienda;
use App\Models\SubcategoriaTienda;
use App\Models\PaginaExterna;
use App\Models\Tienda;
use App\Models\Categoria;
use App\Models\Subcategoria;


class DruniCategoriaScrapingController extends Controller
{
    private $nombre = "druni";
    private $pageUrl = "https://www.druni.es";


    private $id_tienda = 1;

    
    public function category(Client $client)
    {
        $pageUrl = $this->pageUrl;

        // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
        $crawler = $client->request('GET', $pageUrl);
        $this->extractCategoryFrom($crawler);
    }


    public function extractCategoryFrom(Crawler $crawler)
    {
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"ui-menu-dropdown depth-1"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($categoryNode) {
            // Recogemos todas las 'a' 
            $a = $categoryNode->filter('a')->text();
            // Nombre de la categoria sin espacios
            $nombreCategoria = trim($a);
            $nombreCategoria = strtolower($nombreCategoria);
            $nombreCategoria = $this->limpiarAcentos($nombreCategoria);

            // Recoger categorias
            $ListCategory = $this->recogerCategorias();
            $countListCat = count($ListCategory);
            // Comprovamos que es la categoria que nos interesa
            for ($i = 0; $i<$countListCat; $i++)
            {

                similar_text($ListCategory[$i], $nombreCategoria, $porciento);

                if ($porciento > 80)
                { 
                    // Recogemos la ruta de la categoria
                    $ruta_categoria = $categoryNode->filter('a')->attr('href');

                    $id_tienda = $this->id_tienda;
                    // Añadimos en la base de datos la categoria de esta tienda
                    $this->crearCategoriaTienda($nombreCategoria, $ruta_categoria);

                    // Volvemos a filtrar la página para obtener las subclases pero por ello tendremos que volver a recorrerlo y volveremos a encontrarnos con categorias
                    $pageUrl = $this->pageUrl;
                    $client = new Client();
                    $inlineProductStyles = '"menuitem"';
                    $categoryNode->filter("[role=$inlineProductStyles]")->each(function($categoryNode) {
                        
                        // Nombre de la categoria o subcategoria sin espacios
                        $nombreCategoriaYSubcategoria = trim($categoryNode->text());
                        $nombreCategoriaYSubcategoria = $this->limpiarAcentos($nombreCategoriaYSubcategoria);
                        $nombreCategoriaYSubcategoria = strtolower($nombreCategoriaYSubcategoria);
                        
                        $ListCategory = $this->recogerCategorias();
                        // Comprovamos de recoger las subcategorias de las categorias que nos interesan 

                        if (in_array($nombreCategoriaYSubcategoria, $ListCategory))
                        {
                            $ListSubcategory = $this->recogerSubcategorias();
                            echo ("<br> Es una categoria: " . $nombreCategoriaYSubcategoria);
                            
                            if  (in_array($nombreCategoriaYSubcategoria, $ListSubcategory))
                            {
                                echo "<br> Es una Subcategoria: " . $nombreCategoriaYSubcategoria;
                                // Recoger subcategorias
                                $ListSubcategory = $this->recogerSubcategorias();
                                $countListSubcat = count($ListSubcategory);
                                for ($i = 0; $i<$countListSubcat; $i++)
                                {   
    
                                    // Recoger subcategorias
                                    $ListSubcategory = $this->recogerSubcategorias();
    
                                    similar_text($ListSubcategory[$i], $nombreCategoriaYSubcategoria, $porciento);
                                    if ($porciento > 80)
                                    {
                                        // Comprovamos que no recojamos una categoria o una subcategoria no correspondiente
    
                                        // Ruda de la subcategoria
                                        $ruta_subcategoria = $categoryNode->attr('href');
                                        // Recoger la id
    
                                        // Añadimos en la base de datos la subcategoria de esta tienda
                                        $this->crearSubcategoriaTienda($nombreCategoriaYSubcategoria, $ruta_subcategoria);
    
                                        break;
                                    }
                                }
                            }
                        }

                    });
                    echo "SALIMOS";
                    break;
                }
                echo "<br> ------------------------ CONTINUAMOS" ;
            }
        });


    }



    // Recoger las categorias
    public function recogerCategorias()
    {
        $categorias = Categoria::all();
        $ListCategory = [];
        foreach($categorias as $categoria)
        {
            array_push($ListCategory, $categoria->nombre) ;
        }
        return $ListCategory;
    }

    // Recoger las subcategorias
    public function recogerSubcategorias()
    {
        $subcategorias = Subcategoria::all();
        $ListSubcategory = [];
        foreach($subcategorias as $subcategoria)
        {
            $NomSubcategoria = $this->limpiarAcentos($subcategoria->nombre);
            $NomSubcategoria = strtolower($NomSubcategoria);
            array_push($ListSubcategory, $NomSubcategoria) ;
        }
        return $ListSubcategory;
    }

    // IdSubcategoria
    public function limpiarAcentos($cadena)
    {
        $no_permitidas= array ("á","é","í","ó","ú","ñ","à","è","è","ò","ù");
        $permitidas= array ("a","e","i","o","u","n","a","e","i","o","u");
        $texto = str_replace($no_permitidas, $permitidas ,$cadena);
        return $texto;
    }

    
    // IdSubcategoria
    public function recogerIdTienda()
    {
        $nombre = $this->nombre;

        $tienda = Tienda::all()->where('nombre', '=',$nombre)->first();
        $id_tienda = $tienda->id;
        return $id_tienda ;
    }

    // Id Categoria
    public function recogerIdCategoria($nombreCategoria)
    {
        $categorias = Categoria::all()->where('nombre', '=',$nombreCategoria)->first();
        $id_categoria = $categorias->id;
        return $id_categoria ;
    }

    // Id Subcategoria
    public function recogerIdSubcategoria($nombreCategoriaYSubcategoria)
    {
        $subcategorias = Subcategoria::all();
        foreach($subcategorias as $subcategoria)
        {
            $NombreSubC = $subcategoria->nombre;
            $nombreCategoriaYSubcategoria = $this->limpiarAcentos($NombreSubC);
            $NombreSubC = strtolower($NombreSubC);
            similar_text($NombreSubC, $nombreCategoriaYSubcategoria, $porciento);
            if ($porciento > 80)
            {

                $id_subcategoria = $subcategoria->id;
                
                return $id_subcategoria ;
            }
        }
    }

    public function crearCategoriaTienda($nombreCategoria, $ruta_categoria)
    {
        $id_tienda = $this->recogerIdTienda();
        $id_categoria = $this->recogerIdCategoria($nombreCategoria);
        CategoriaTienda::create([
            'nombre' => $nombreCategoria,
            'id_categoria' => $id_categoria,
            'url_categoria' => $ruta_categoria,
            'id_tienda' => $id_tienda
        ]);
    }
    
    
    public function crearSubcategoriaTienda($nombreCategoriaYSubcategoria, $ruta_subcategoria)
    {
        $id_subcategoria = $this->recogerIdSubcategoria($nombreCategoriaYSubcategoria);
        $id_tienda = $this->recogerIdTienda();
        SubcategoriaTienda::create([
            'nombre' => $nombreCategoriaYSubcategoria,
            'id_subcategoria' => $id_subcategoria,
            'url_subcategoria' => $ruta_subcategoria,
            'id_tienda' => $id_tienda
        ]);
    }
    


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
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
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
