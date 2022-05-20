<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Producto;
use App\Models\Tienda;
use App\Models\PaginaExterna;
use App\Models\CategoriaTienda;
use App\Models\SubcategoriaTienda;
use App\Models\Precio;

class MaquillaliaScrapingController extends Controller
{
    private $limiteArticulos = 1;
    private $lastPage = 1;
    private $itemsXPage = 20;
    private $nombreTienda = "maquillalia";
    private $url = "https://www.lookfantastic.es/";
    private $urlSubcategoria = "";
    
    // Gastos de envio y minimos
    private $gastosPeninsula = 0;
    private $gastosBaleares = 0;
    private $gastosMinimos= 0;



    public function productsCategory(Client $client)
    {
        $itemLimit = $this->limiteArticulos;
        $ultimaPagina = $this->lastPage;
        $articulosXPagina = $this->itemsXPage;
        // ULTIMA PAGINA
        // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
        $subcategorias = $this->recogerSubcategoriaTienda();
        foreach ($subcategorias as $subcategoria) {

            $urlSubcategoria = $subcategoria->url_subcategoria;
            $this->urlSubcategoria = $urlSubcategoria;
            $pageUrl = "$urlSubcategoria";
            echo "<br> url: " . $pageUrl . "<br>" ;
                
            $crawler = $client->request('GET', $pageUrl);
            $inlineArtId = '"NumPro"';

            $limiteArt = $crawler->filter("[class=$inlineArtId]")->each(function($NumNode) {
                $limiteArtString = $NumNode->filter('strong')->first()->text();
                // Pasamos de string a int para poder hacer la operacion
                $limiteArt = intval($limiteArtString);
                return $limiteArt;
            }); 

            echo "UltimaPagina: " . $limiteArt[0];
            // Pasamos de array a int
            $limiteArt = intval($limiteArt[0]);
            $this->limiteArticulos = $limiteArt;
            // Calculamos cuantas paginas tendra y la redondeamos para arriba
            $ultPagina = $limiteArt / $articulosXPagina;
            $ultPagina = intval(ceil($ultPagina));
            $this->lastPage = $ultPagina;
            $this->extractProductsFrom($crawler, $client ,$urlSubcategoria);
        }
    }


    public function extractProductsFrom(Crawler $crawler, $client, $urlSubcategoria)
    {
        $ultPagina = $this->lastPage;
        for ($i = 0; $i<=$ultPagina; $i++)
        {
            $pageUrl = $urlSubcategoria;
            
            // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
            $crawler = $client->request('GET', $pageUrl);

                // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
                $inlineProductStyles = '"ProductBot"';

                // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
                // con EACH iteramos cada nodo del objeto CRAWLER
                $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode) {
                    $divFather = $productNode->ancestors();
                    $titleNode = $divFather->filter("[class='Title smtt']");
                    $nombreProducto = $titleNode->text();
                    $id_producto = $this->recogerIdProducto($nombreProducto);
                    echo "<BR> ID PRODCUTO: " . $id_producto;
                    if ($id_producto != null)
                    {
                        dd("No es null");

                        // Comprovar si tiene existencias
                        $divStock = $productNode->filter("div");
                        $aStock = $divStock->eq(3);
                        $aNode = $productNode->filter("a");
                        $stock = $aNode->filter("span")->count();
                        // Si hay stock recoger los datos
                        if ($stock != 1)                
                        {
                            echo "HA ENTRADO ---------------";
                            $divs = $productNode->children()->filter('div');
                            $precioString = $divs->attr('data-price');
                            $precio = floatval($precioString);
                            echo "<br> Precio: " . $precioString . "<br>" ;
                            $this->crearPrecio($id_producto, $precio);
                        }
                    }
                }); 
        }
    }


    // RECOGER DATOS DE GASTOS DE ENVIO DE LA PÁGINA
    public function shippingCostData(Client $client)
    {
        // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
        $crawler = $client->request('GET', 'https://www.maquillalia.com/envios-devoluciones-i-1.html');
        $this->extractShippingCostsFrom($crawler,);
    }

    public function extractShippingCostsFrom(Crawler $crawler)
    {
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter('u')->each(function($dataNode) {

            $comprovarPB = $dataNode->text();

            // Comprovar si es peninsula o baleares
            if(str_contains($comprovarPB, "Península"))
            {
                $client = new Client();
                $crawler = $client->request('GET', 'https://www.maquillalia.com/envios-devoluciones-i-1.html');

                // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
                $inlineProductStyles = '"information_contenido fced"';
                // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
                // con EACH iteramos cada nodo del objeto CRAWLER
                $crawler->filter("[class=$inlineProductStyles]")->each(function($dataNode) {
                    
                    $ps = $dataNode->filter('p')->text();
                    $pArrayUnclean = explode("a ", $ps);
                    $pArrayClean = explode("-", $pArrayUnclean[14]);

                    // Gastos Peninsula 
                    $gastosPeninsulaSymbol = explode("€", $pArrayClean[1]);
                    $gastosPeninsulaUncleanArray = trim($gastosPeninsulaSymbol[0]);
                    $gastosPeninsulaClean = str_replace(',','.',$gastosPeninsulaUncleanArray);

                    $gastosPeninsula =  floatval($gastosPeninsulaClean);
                    $this->gastosPeninsula = $gastosPeninsula;
                    echo "<br> ---- Peninsula: " . $gastosPeninsula;

                    // Gastos minimos 
                    $gastosMinimosUnclean = explode("€", $pArrayClean[0]);

                    $gastosMinimos =  intval($gastosMinimosUnclean[0]);
                    echo "<br> Gastos minimos: " . $gastosMinimos ;
                    $this->gastosMinimos = $gastosMinimos;

                });
            }
            elseif(str_contains($comprovarPB, "Baleares"))
            {
                $fatherData = $dataNode->ancestors()->ancestors();

                $ps = $fatherData->filter('p');
                $pNode = $ps->eq(7)->html();
                $pArrayUnclean = explode("a ", $pNode);
                $pArrayClean = explode("-", $pArrayUnclean[4]);
                $gastosBalearesClean = str_replace(',','.',$pArrayClean[1]);
                $gastosBalearesSymbol = explode("€", $gastosBalearesClean);
                $gastosBaleares = $gastosBalearesSymbol[0];
                echo "<br> ---- Baleares: " . $gastosBaleares;
                $this->gastosBaleares = $gastosBaleares;
            }

        }); 

        // Nombre de la tienda
        $this->crearTienda();
        $this->crearPaginaExterna();
    }

    // Crear tienda
    public function crearTienda()
    {
        // Recoger atributos
        $gastosPeninsula = $this->gastosPeninsula;
        $gastosBaleares = $this->gastosBaleares;
        $gastosMinimos = $this->gastosMinimos;
        $nombreTienda = $this->nombreTienda;

        $createDB = Tienda::create([
            "nombre" => $nombreTienda,
            "gastos_peninsula" => $gastosPeninsula,
            "gastos_baleares" => $gastosBaleares,
            'gastos_minimos' => $gastosMinimos,
        ]);
    }

    public function recogerSubcategoriaTienda()
    {
        $id_tienda = $this->recogerIdTienda();
        $subcategorias = SubcategoriaTienda::all()->where('id_tienda', '=',$id_tienda);
        return $subcategorias;
    }
    

    // IdTienda
    public function recogerIdTienda()
    {
        //Nombre de la tienda
        $nombreTienda = $this->nombreTienda;

        $tiendas = Tienda::all();
        foreach ($tiendas as $tienda)
        {
            if ($tienda->nombre == $nombreTienda)
            {
                return $tienda->id;
            }
            // else{
            //     return redirect()->route('index')->with('error', 'Error searching store id');            
            // }
        }   
    }

    
    // IdSubcategoria
    public function recogerIdSubcategoria()
    {
        $urlSubcategoria = $this->urlSubcategoria;
        $subcategorias = $this->recogerSubcategoriaTienda();
        foreach ($subcategorias as $subcategoria)
        {
            echo "<br> SUBURL: ". $subcategoria->url_subcategoria   . "URL: ". $urlSubcategoria;
            if ($subcategoria->url_subcategoria == $urlSubcategoria)
            {
                echo "<br> ------------------- SUBCATEGORIA ID: " . $subcategoria->id;

                return $subcategoria->id;
            }
            // else{
            //     return redirect()->route('/')->with('error', 'Error searching category id');            
            // }
        }
    }

    // IdProducto
    public function recogerIdProducto($nombreProducto)
    {
        $id_subcategoria = $this->recogerIdSubcategoria();
        dd($id_subcategoria);
        $productos = Producto::all()->where("id_subcategoria", $id_subcategoria);
        foreach ($productos as $producto)
        {
            $NameProduct = $producto->nombre;
            similar_text($nombreProducto, $NameProduct, $porciento);
            echo "<br> PORCENTAJE: ". $porciento;
            if ($porciento > 60)
            {
                dd("entra");
                return $producto->id;
            }
            // else{
            //     return redirect()->route('/')->with('error', 'Error searching product id');            
            // }
        }
    }


    // Crear pagina externa 
    public function crearPaginaExterna()
    {
        try {
            //Url de la tienda
            $url = $this->url;
            //Url de la tienda
            $id_tienda = $this->recogerIdTienda();


            $createDB = PaginaExterna::create([
                "url" => $url,
                "id_tienda" => $id_tienda,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

    }

    // Crear precios 
    public function crearPrecio($id_producto , $precio)
    {
        $id_tienda = $this->recogerIdTienda();
        $createDB = Precio::create([
            "id_producto" => $id_producto,
            'id_tienda'=> $id_tienda,
            "precio" => $precio,
        ]);
    }
}
