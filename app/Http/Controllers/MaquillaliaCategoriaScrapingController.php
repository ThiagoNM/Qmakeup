<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\CategoriaScraping;
use App\Models\Categoria;
use App\Models\CategoriaTienda;
use App\Models\Subcategoria;
use App\Models\SubcategoriaTienda;
use App\Models\Tienda;
use Illuminate\Http\Request;

class MaquillaliaCategoriaScrapingController extends Controller
{
    // URL de la página
    private $nombreTienda = "maquillalia";
    private $pageUrl = "https://www.maquillalia.com";
    
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
        $inlineProductStyles = '"sbmn"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($categoryNode) {
            // Recogemos todos los "li"
            $lis = $categoryNode->filter('li')->text();
            // Nombre de la categoria sin espacios de más 
            $nombreCategoria = strtolower(trim($lis));
            
            // Recogemos las categorias que tenemos de la tabla CATEGORIAS
            $ListCategory = $this->recogerCategorias();
            $countListCat = count($ListCategory);
            // Comprovamos que es la categoria que nos interesa
            for ($i = 0; $i<$countListCat; $i++)
            {

                if ($nombreCategoria == $ListCategory[$i])
                {
                    // Ruta de la categoria para más a delante recoger todos los productos de esa categoria 
                    $ruta_categoria = "";
                    $this->crearCategoriaTienda($nombreCategoria, $ruta_categoria);
                    // Volvemos a filtrar la página para obtener las subclases 
                    $pageUrl = $this->pageUrl;
                    $client = new Client();

                    $categoryNode->filter('a')->each(function($categoryNode) {
                        // Nombre de la subcategoria sin espacios
                        $nombreSubcategoria = trim($categoryNode->text());

                        // Volvemos a filtrar la página para obtener las subcategorias y no recoga otra vez las categorias
                        $ListSubcategory = $this->recogerSubcategorias();
                        $countListSubcat = count($ListSubcategory);
                        $ListCategory = $this->recogerCategorias();

                        if (!in_array($nombreSubcategoria, $ListCategory))
                        {
                            for ($i = 0; $i<$countListSubcat; $i++)
                            {   
                                // Comprovamos que no recojamos una categoria
                                similar_text($ListSubcategory[$i] ,$nombreSubcategoria, $porciento);
                                if ($porciento > 80)
                                {
                                    // Ruta de la subcategoria para más a delante recoger todos los productos de esa categoria 
                                    $ruta_subcategoria = $categoryNode->attr('data-href');
                                    // Devido a que hay categorias que tienen la ruta en el atributo data-href o href lo comprovamos para no recoger una ruta vacia
                                    if($ruta_subcategoria == "")
                                    {
                                        $ruta_subcategoria = $categoryNode->attr('href');
                                    }
                                    $this->crearSubcategoriaTienda($nombreSubcategoria, $ruta_subcategoria);
                                    break;
                                }
                            }
                        }
                    });
                }
            }
        });
        echo "Terminado";
    }
    

    // Elimina los acentos
    public function limpiarAcentos($cadena)
    {
        $no_permitidas= array ("á","é","í","ó","ú","ñ","à","è","ì","ò","ù");
        $permitidas= array ("a","e","i","o","u","n","a","e","i","o","u");
        $texto = str_replace($no_permitidas, $permitidas ,$cadena);
        return $texto;
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


    // Recoger las subcategorias
    public function recogerSubcategoriasTienda()
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

    
    // Id de la tienda en la que estamos
    public function recogerIdTienda()
    {
        $nombreTienda = $this->nombreTienda;
        $tienda = Tienda::all()->where('nombre', '=',$nombreTienda)->first();
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



    public function crearCategoriaTienda($nombreCategoria, $ruta_categoria)
    {
        try {
            $id_tienda = $this->recogerIdTienda();
            $ExisteCategoria = CategoriaTienda::where("nombre", $nombreCategoria)->where("id_tienda", $id_tienda)->exists();
            if(!$ExisteCategoria)
            {
                $id_categoria = $this->recogerIdCategoria($nombreCategoria);
                CategoriaTienda::create([
                    'nombre' => $nombreCategoria,
                    'id_categoria' => $id_categoria,
                    'url_categoria' => $ruta_categoria,
                    'id_tienda' => $id_tienda
                ]);
            }
        } catch (\Throwable $th) {
            echo "Error: " . $th;
        }


    }
    
    // Id Subcategoria
    public function recogerIdSubcategoria($nombreSubcategoria)
    {
        $nombreSubcategoria = $this->limpiarAcentos($nombreSubcategoria);
        $nombreSubcategoria = strtolower($nombreSubcategoria);

        $subcategorias = Subcategoria::all();
        foreach($subcategorias as $subcategoria)
        {
            $NombreSubC = $subcategoria->nombre;
            $NombreSubC = $this->limpiarAcentos($NombreSubC);
            $NombreSubC = strtolower($NombreSubC);

            similar_text($NombreSubC, $nombreSubcategoria, $porciento);
            if ($porciento > 75)
            {
                $listaSubcatDeSubcat = ['base de maquillaje en crema', 'base de maquillaje en mousse', 'base de maquillaje en polvo', 'base de maquillaje fluida'];
                for ($y=0;$y<count($listaSubcatDeSubcat);$y++)
                {
                    similar_text($listaSubcatDeSubcat[$y], $nombreSubcategoria, $porciento);
                    if ($porciento > 98)
                    {
                        $id_subcategoria = $subcategorias->where("nombre" , "=", "Bases de maquillaje")->first()->id;
                        return $id_subcategoria ;
                    }
                }

                $id_subcategoria = $subcategoria->id;
                
                return $id_subcategoria ;
            }
            
        }
        // Comprovar de que no sea la subcategoria "Contorno maquillaje" con las variantes y sinonimos
        $listaSubcatDeSubcat = ['bronceador', 'bronceadores en crema', 'Bronceadores en polvo'];
        for ($y=0;$y<count($listaSubcatDeSubcat);$y++)
        {
            similar_text($listaSubcatDeSubcat[$y], $nombreSubcategoria, $porciento);
            if ($porciento > 85)
            {
                $id_subcategoria = $subcategorias->where("nombre" , "=", "Contorno maquillaje")->first()->id;
                return $id_subcategoria ;
            }
        }

        // Comprovar de que no sea la subcategoria "Coloretes" con las variantes y sinonimos
        $listaSubcatDeSubcat = ['colorete en crema', 'colorete en polvo', 'colorete en liquido'];
        for ($y=0;$y<count($listaSubcatDeSubcat);$y++)
        {
            similar_text($listaSubcatDeSubcat[$y], $nombreSubcategoria, $porciento);
            if ($porciento > 85)
            {
                $id_subcategoria = $subcategorias->where("nombre" , "=", "Coloretes")->first()->id;
                return $id_subcategoria ;
            }
        }
        // Comprovar de que no sea la subcategoria "Correctores de maquillaje" con las variantes y sinonimos
        $listaSubcatDeSubcat = ['correctores','correctores en crema', 'correctores fluidos'];
        for ($y=0;$y<count($listaSubcatDeSubcat);$y++)
        {
            similar_text($listaSubcatDeSubcat[$y], $nombreSubcategoria, $porciento);
            if ($porciento > 85)
            {
                $id_subcategoria = $subcategorias->where("nombre" , "=", "Correctores de maquillaje")->first()->id;
                return $id_subcategoria ;
            }
        }
        // Comprovar de que no sea la subcategoria "Fijadores maquillaje" con las variantes y sinonimos
        $listaSubcatDeSubcat = ['fijadores y prebases'];
        for ($y=0;$y<count($listaSubcatDeSubcat);$y++)
        {
            similar_text($listaSubcatDeSubcat[$y], $nombreSubcategoria, $porciento);
            if ($porciento > 85)
            {
                $id_subcategoria = $subcategorias->where("nombre" , "=", "Fijadores maquillaje")->first()->id;
                return $id_subcategoria ;
            }
        }
        // Comprovar de que no sea la subcategoria "Iluminadores de maquillaje" con las variantes y sinonimos
        $listaSubcatDeSubcat = ['iluminador en crema', 'iluminador en polvo', 'iluminador fluido'];
        for ($y=0;$y<count($listaSubcatDeSubcat);$y++)
        {
            similar_text($listaSubcatDeSubcat[$y], $nombreSubcategoria, $porciento);
            if ($porciento > 85)
            {
                $id_subcategoria = $subcategorias->where("nombre" , "=", "Iluminadores de maquillaje")->first()->id;
                return $id_subcategoria ;
            }
        }
        // Comprovar de que no sea la subcategoria "Iluminadores de maquillaje" con las variantes y sinonimos
        $listaSubcatDeSubcat = ['polvos compactos / sueltos','polvos compactos', 'polvos sueltos'];
        for ($y=0;$y<count($listaSubcatDeSubcat);$y++)
        {
            similar_text($listaSubcatDeSubcat[$y], $nombreSubcategoria, $porciento);
            if ($porciento > 85)
            {
                $id_subcategoria = $subcategorias->where("nombre" , "=", "Fijadores maquillaje")->first()->id;
                return $id_subcategoria ;
            }
        }

    }

    // Crear la subcategoria de la tienda
    public function crearSubcategoriaTienda($nombreSubcategoria, $ruta_subcategoria)
    {
        try {
            $id_subcategoria = $this->recogerIdSubcategoria($nombreSubcategoria);
            if ($id_subcategoria != null)
            {            
                $id_tienda = $this->recogerIdTienda();
                $ExisteSubcategoria = SubcategoriaTienda::where("nombre", $nombreSubcategoria)->where("id_tienda", $id_tienda)->exists();
                if(!$ExisteSubcategoria)
                {
                    SubcategoriaTienda::create([
                        'nombre' => $nombreSubcategoria,
                        'id_subcategoria' => $id_subcategoria,
                        'url_subcategoria' => $ruta_subcategoria,
                        'id_tienda' => $id_tienda
                    ]);
                }
            }
        } catch (\Throwable $th) {
            echo "Error: " . $th;
        }
    }

}
