<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Producto;

class MaquillaliaScrapingController extends Controller
{
<<<<<<< HEAD
    private $limiteArticulos = 1;
    private $lastPage = 1;
    private $itemsXPage = 20;
    private $nombre = "Maquillalia";

    public function productsCategory(Client $client)
    {
        $itemLimit = $this->limiteArticulos;
        $ultimaPagina = $this->lastPage;
        $articulosXPagina = $this->itemsXPage;
        // ULTIMA PAGINA
        // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
     
        $categorias = ['labiales-liquidos-c-1678_17_1685']; 
        foreach ($categorias as $categoria) {

            $pageUrl = "https://www.maquillalia.com/{$categoria}.html";
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
            $this->extractProductsFrom($crawler, $client ,$categoria);
        }
    }


    public function extractProductsFrom(Crawler $crawler, $client, $categoria)
    {
        $ultPagina = $this->lastPage;
        for ($i = 0; $i<=$ultPagina; $i++)
        {
            echo "<br> --------------- Pagina: " . $i . "<br>"; 

            // $pageUrl = "https://www.maquillalia.com/{$categoria}.html?page={$i}";

            echo "<br> url: " . $pageUrl . "<br>" ;
            // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
            $crawler = $client->request('GET', $pageUrl);

                // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
                $inlineProductStyles = '"ProductBot"';

                // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
                // con EACH iteramos cada nodo del objeto CRAWLER
                $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode) {

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
                    }

                    // $product = $this->extractProductInfo($img, $marca, $precio, $nombre, $categoria, $descripcion, $valoracion, $idPagina );
                }); 
        }
    }

    // Nuevo
    public function createPrecios($idProducto ,$idPagina, $precio)
    {
        Precios::create([
            "id_producto" => $idProducto,
            'id_pagina'=> $idPagina,
            "precio" => $precio,
        ]);
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
            echo "<br> Comprovar: " . $comprovarPB;


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
                    echo "<br> ---- Peninsula: " . $gastosPeninsula;

                    // Gastos minimos 
                    $gastosMinimosUnclean = explode("€", $pArrayClean[0]);

                    $gastosMinimos =  intval($gastosMinimosUnclean[0]);
                    echo "<br> Gastos minimos: " . $gastosMinimos ;
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
            }

            // Nombre de la tienda
            $nombre = $this->nombre;
            $this->crearTiendas($nombre ,$gastosPeninsula, $gastosBaleares, $gastosMinimos );
            
        }); 
    }

    public function crearTiendas($nombre ,$gastosPeninsula, $gastosBaleares, $gastosMinimos )
    {
        Tiendas::create([
            "nombre" => $nombre,
            "gastos_peninsula" => $gastosPeninsula,
            "gastos_baleares" => $gastosBaleares,
            'gastos_minimos' => $gastosMinimos,
        ]);
    }
=======
    public function productsCategory(Client $client)
    {
        // Categoria::all();
        // $categorias = ['ojos', 'rostro', 'unas', 'labios']; 
        // foreach ($categorias as $categoria) {
            for ($i = 0; $i<=2; $i++)
            {
                echo "Pagina: " . $i;
                // $offset = $i ++;
                $pageUrl = "https://www.maquillalia.com/labiales-liquidos-c-1678_17_1685.html?page={$i}";
            
                // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
                $crawler = $client->request('GET', $pageUrl);
                $this->extractProductsFrom($crawler);
            }
            

        // }
    }


    public function extractProductsFrom(Crawler $crawler)
    {
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"prdt Product"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode) {
            
            $divs = $productNode->children()->filter('div');
            $priceNode = $divs->eq(4);
            $precioString = $priceNode->attr('data-price');
            $precio = floatval($precioString);
            dd($precio);

            // $product = $this->extractProductInfo($img, $marca, $precio, $nombre, $categoria, $descripcion, $valoracion, $idPagina );
        }); 


    }

    public function extractProductInfo($img ,$marca, $precio, $nombre, $categoria, $descripcion, $valoracion, $idPagina )
    {
        // Producto::create([
        //     "imagen" => $img,
        //     "marca" => $marca,
        //     "precio" => $precio,
        //     "nombre" => $nombre,
        //     'categoria' => $categoria,
        //     "descripcion" => $descripcion,
        //     'valoracion'=> 0,
        //     'id_pagina'=> $idPagina
        // ]);
    }    
>>>>>>> b6b9043cd608abe61ad1144d046b1ac4966c4bf5
}
