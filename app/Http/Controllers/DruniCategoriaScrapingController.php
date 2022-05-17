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
    private $nombreTienda = "druni";
    private $pageUrl = "https://www.druni.es";
    private $EsCategoria = false;


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
                    // Volemos a recorrer el creawler pero a partir de la categoria para recoger las subcategorias
                    $pageUrl = $this->pageUrl;
                    $client = new Client();
                    $inlineProductStyles = '"menuitem"';
                    $categoryNode->filter("[role=$inlineProductStyles]")->each(function($categoryNode) {
                        $this->EsCategoria = false;

                        // Nombre de la categoria o subcategoria sin espacios
                        $nombreCategoriaYSubcategoria = trim($categoryNode->text());
                        $nombreCategoriaYSubcategoria = $this->limpiarAcentos($nombreCategoriaYSubcategoria);
                        $nombreCategoriaYSubcategoria = strtolower($nombreCategoriaYSubcategoria);
                        
                        // Recoger categorias
                        $ListCategory = $this->recogerCategorias();
                        $countListCat = count($ListCategory);
                        $ListaCategoriasYaRecogidas = [];
                        $ListaSubcategoriasYaRecogidas = [];

                        // For para recoger las CATEGORIAS
                        // Comprovamos de recoger las subcategorias de las categorias que nos interesan 
                        for ($i = 0; $i<$countListCat; $i++)
                        {
                            similar_text($ListCategory[$i], $nombreCategoriaYSubcategoria, $porciento);
                            if ($porciento > 80)
                            {
                                if (!in_array($nombreCategoriaYSubcategoria, $ListaCategoriasYaRecogidas))
                                {
                                    $ruta_categoria = $categoryNode->filter('a')->attr('href');
                                    // Añadimos la categoria en la base de datos
                                    $this->crearCategoriaTienda($nombreCategoriaYSubcategoria, $ruta_categoria);
                                    array_push($ListaCategoriasYaRecogidas, $nombreCategoriaYSubcategoria);
                                    $this->EsCategoria = true;
                                    break;
                                }
                                else{
                                    $this->EsCategoria = false;
                                }
                            }

                        }
                        $EsCategoria = $this->EsCategoria;
                        // Comprovamos de recoger las subcategorias de las categorias que nos interesan 
                        if(!$EsCategoria)
                        {
                            $ListSubcategory = $this->recogerSubcategorias();
                            $countListSubcat = count($ListSubcategory);

                            for ($x = 0; $x<$countListSubcat; $x++)
                            {
    
                                // For para recoger las SUBCATEGORIAS
                                // Recoger subcategorias
    
                                similar_text($ListSubcategory[$x], $nombreCategoriaYSubcategoria, $porciento);
                                if ($porciento > 85)
                                {
                                    if (!in_array($nombreCategoriaYSubcategoria, $ListaSubcategoriasYaRecogidas))
                                    {
                                        $ruta_subcategoria = $categoryNode->attr('href');
                                        // Añadimos en la base de datos la subcategoria de esta tienda
                                        $this->crearSubcategoriaTienda($nombreCategoriaYSubcategoria, $ruta_subcategoria);
                                        array_push($ListaSubcategoriasYaRecogidas, $nombreCategoriaYSubcategoria);
                                        break;
                                    }
                                }
                            }
                        }

                    });
               }
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
            $nombreCategoria = $categoria->nombre;
            $nombreCategoria = strtolower($nombreCategoria);
            array_push($ListCategory, $nombreCategoria) ;
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

    // Elimina los acentos
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
        $nombreTienda = $this->nombreTienda;

        $tienda = Tienda::all()->where('nombre', $nombreTienda)->first();
        $id_tienda = $tienda->id;
        return $id_tienda ;
    }

    // Id Categoria
    public function recogerIdCategoria($nombreCategoria)
    {
        $categorias = Categoria::all()->where('nombre', $nombreCategoria)->first();
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
            $NombreSubC = $this->limpiarAcentos($NombreSubC);
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
        try {
            $id_tienda = $this->recogerIdTienda();
            $id_categoria = $this->recogerIdCategoria($nombreCategoria);
            CategoriaTienda::create([
                'nombre' => $nombreCategoria,
                'id_categoria' => $id_categoria,
                'url_categoria' => $ruta_categoria,
                'id_tienda' => $id_tienda
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

    }
    
    
    public function crearSubcategoriaTienda($nombreCategoriaYSubcategoria, $ruta_subcategoria)
    {
        try {
            $id_subcategoria = $this->recogerIdSubcategoria($nombreCategoriaYSubcategoria);
            $id_tienda = $this->recogerIdTienda();
            SubcategoriaTienda::create([
                'nombre' => $nombreCategoriaYSubcategoria,
                'id_subcategoria' => $id_subcategoria,
                'url_subcategoria' => $ruta_subcategoria,
                'id_tienda' => $id_tienda
            ]);
            return "Hola";
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
