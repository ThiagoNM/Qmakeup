<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\CategoriaScraping;
use Illuminate\Http\Request;
use App\Models\Tienda;
use App\Models\PaginaExterna;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\CategoriaTienda;
use App\Models\SubcategoriaTienda;

class LookfantasticCategoriaScrapingController extends Controller
{
    private $nombre = "lookfantastic";
    private $EsCategoria = false;
    private $pageUrl = "https://www.lookfantastic.es";
    
    private $ListaCategoriasYaRecogidas = [];


    // Para hacer una solicitud a la página
    public function category(Client $client)
    {
        $pageUrl = $this->pageUrl;

        // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
        $crawler = $client->request('GET', $pageUrl);
        $this->extractCategoryFrom($crawler);
    }

    // Recoger las categorias de la pagina
    public function extractCategoryFrom(Crawler $crawler)
    {
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"responsiveFlyoutMenu_levelTwoItem"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER


        $crawler->filter("[class=$inlineProductStyles]")->each(function($categoryNode) {
            $ListaCategoriasYaRecogidas = $this->ListaCategoriasYaRecogidas;

            // Recogemos las categorias que tenemos de la tabla CATEGORIAS
            $ListCategory = $this->recogerCategorias();
            $countListCat = count($ListCategory);
            $uls = $categoryNode->filter('span');
            $ulNode = $uls->filter("[class='responsiveFlyoutMenu_levelTwoLinkText']");
            $nombreCategoria = $ulNode->text();

            // Nombre de la categoria sin espacios
            $nombreCategoria = $this->limpiarAcentos($nombreCategoria);
            $nombreCategoria = strtolower($nombreCategoria);
            $nombreCategoria = trim($nombreCategoria);
            // Comprovamos que es la categoria que nos interesa
            for ($i = 0; $i<$countListCat; $i++)
            {
                echo "<br> Lista categoria: " . $ListCategory[$i] .  "<br> Nombre Categoria: " . $nombreCategoria ;

                if ($nombreCategoria == $ListCategory[$i] AND !in_array($nombreCategoria, $ListaCategoriasYaRecogidas))
                {
                    // Recogemos la ruta de la categoria
                    $ruta_categoria = $categoryNode->filter('a')->attr('href');
                    
                    // Volvemos a filtrar la página para obtener las subclases 
                    $pageUrl = $this->pageUrl;
                    $client = new Client();
                    
                    echo "<br>" . "Categoria: " . $nombreCategoria . "<br>";
                    // Creamos una categoria en la base de datos en la tabla CATEGORIA_PÁGINA
                    $this->crearCategoriaTienda($nombreCategoria, $ruta_categoria);
                    echo "<br> CATEGORIA CREADA <br>";
                    array_push($ListaCategoriasYaRecogidas, $nombreCategoria);

                    $inlineProductStyles = '"subnav-level-three"';

                    // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
                    // con EACH iteramos cada nodo del objeto CRAWLER
                    $categoryNode->filter("[data-subnav-level=$inlineProductStyles]")->each(function($categoryNode) {
                        // Recogemos la ruta de la subcategoria
                        $r_subcategoria = $categoryNode->filter("[class='responsiveFlyoutMenu_levelThreeLink']");
                        
                        // Juntamos la URL de la pagina más la ruta de la subcategoria para poder ontenerla entera y despues poder recoger los poductos de esa subcategoria.
                        $pageUrl = $this->pageUrl;
                        $ruta_subcategoria = $pageUrl . $r_subcategoria->attr('href');
                        // Nombre de la subcategoria
                        $nombreSubcategoria = $r_subcategoria->filter("[class='responsiveFlyoutMenu_levelThreeLinkText']")->text();
                        $ListSubcategory = $this->recogerSubcategorias();
                        $countListSubc = count($ListSubcategory);
                        for ($x=0;$x<$countListSubc;$x++)
                        {
                            similar_text($ListSubcategory[$x], $nombreSubcategoria, $porciento);
                            if ($porciento > 70)
                            {
                                echo "<br>" . " SUBCATEGORIA CREADA: " . $nombreSubcategoria. "<br>";
                                $this->crearSubcategoriaTienda($nombreSubcategoria, $ruta_subcategoria);
                                break;
                            }
                        }
                    });

                }
            }
        });
    }

    // Elimina los acentos
    public function limpiarAcentos($cadena)
    {
        $no_permitidas= array ("á","é","í","ó","ú","ñ","à","è","è","ò","ù");
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



    public function crearCategoriaTienda($nombreCategoria, $ruta_categoria)
    {
        try{
            $id_tienda = $this->recogerIdTienda();
            $id_categoria = $this->recogerIdCategoria($nombreCategoria);
            CategoriaTienda::create([
                'nombre' => $nombreCategoria,
                'id_categoria' => $id_categoria,
                'url_categoria' => $ruta_categoria,
                'id_tienda' => $id_tienda
            ]);
        } catch (\Throwable $th) {
            echo "Error: " . $th;
        }
    }
    
    // Id Subcategoria
    public function recogerIdSubcategoria($nombreSubcategoria)
    {
        $nombreSubcategoria = strtolower($nombreSubcategoria);

        $subcategorias = Subcategoria::all();
        foreach($subcategorias as $subcategoria)
        {
            $NombreSubC = $subcategoria->nombre;
            $NombreSubC = $this->limpiarAcentos($NombreSubC);
            $NombreSubC = strtolower($NombreSubC);
            echo "<br> NOMBRE SUBCATEGORIA DENTRO: " . $NombreSubC;
            similar_text($NombreSubC, $nombreSubcategoria, $porciento);
            if ($porciento > 70)
            {
                echo "ENTRA!!!!";
                $id_subcategoria = $subcategoria->id;
                
                return $id_subcategoria ;
            }
        }
    }
    
    public function crearSubcategoriaTienda($nombreSubcategoria, $ruta_subcategoria)
    {
        try {
            $id_subcategoria = $this->recogerIdSubcategoria($nombreSubcategoria);
            $id_tienda = $this->recogerIdTienda();
            echo "<br> ID SUBCATEGORIA" . $id_subcategoria;
            echo "<br> ID TIENDA" . $id_tienda;

            SubcategoriaTienda::create([
                'nombre' => $nombreSubcategoria,
                'id_subcategoria' => $id_subcategoria,
                'url_subcategoria' => $ruta_subcategoria,
                'id_tienda' => $id_tienda
            ]);
        } catch (\Throwable $th) {
            echo "Error: " . $th;
        }

    }

}
