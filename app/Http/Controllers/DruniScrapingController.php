<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Producto;

class DruniScrapingController extends Controller
{
    private $nombre = "Druni";
    private $limiteArticulos = 1;
    private $lastPage = 1;
    private $itemsXPage = 24;


    public function productsCategory(Client $client)
    {
        $itemLimit = $this->limiteArticulos;
        $ultimaPagina = $this->lastPage;
        $articulosXPagina = $this->itemsXPage;

        // Filtramos el objeto para "crawler" para obtener la cantidad de articulos que hay
        
        // Categoria::all();
        $subcategorias = ['labios']; 
        foreach ($subcategorias as $subcategoria) {

            $pageUrl = "https://www.druni.es/maquillaje/{$subcategoria}";

            // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
            $crawler = $client->request('GET', $pageUrl);
            $inlineArtId = '"maincontent"';
            $limiteArt = $crawler->filter("[id=$inlineArtId]")->each(function($NumNode) {

                // Filtramos el contenedor para recoger una información especifica
                $limiteArt = $NumNode->filter("[class='toolbar-number']")->first()->text();
                return $limiteArt;
            }); 
            $limiteArt = intval($limiteArt[0]);
            $this->limiteArticulos = $limiteArt;

            $ultPagina = $limiteArt / $articulosXPagina;
            $ultPagina = intval(ceil($ultPagina));
            $this->lastPage = $ultPagina;
            echo "UltimaPagina: " . $ultPagina;

            // -----------------------------------------------------------

            $this->extractProductsFrom($crawler, $client ,$subcategoria);
            
        }
    }


    public function extractProductsFrom(Crawler $crawler,Client $client ,$subcategoria)
    {
        $ultPagina =  $this->lastPage;
        for ($i = 1; $i<=$ultPagina; $i++)
        {
            $pageUrl = "https://www.druni.es/maquillaje/{$subcategoria}?p={$i}";

            // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
            $crawler = $client->request('GET', $pageUrl);


            echo "<br> Pagina: " . $pageUrl;
            echo "<br> subcategoria: " . $subcategoria . "<br>";
        
            // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
            // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
            $inlineProductStyles = '"item product product-item"';

            // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
            // con EACH iteramos cada nodo del objeto CRAWLER
            $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode, $subcategoria) {


                // Comprovar si no esta agotado
                $divAgotado = $productNode->filter("[class='product-item-inner']");
                $estaAgotado = $divAgotado->filter("[class='stock unavailable']")->count();


                if($estaAgotado == 0)
                {
                    // Filtramos el contenedor para recoger una información especifica
                    $img = $productNode->filter("[class='product-image-photo']")->first()->attr('src');

                    $nombre = $productNode->filter("[class='product-item-link']")->first()->text();
                    echo "<br> Nombre: " . $nombre;
                    
                    $marca = $productNode->filter("[class='product-brand']")->first()->text();
                    $descripcion = $productNode->filter("[class='product description product-item-description']")->first()->text();
                    $precioNode = $productNode->filter("[data-price-type='finalPrice']")->first()->text();
                    
                    // Si no tiene el producto un contenedor con la clase "price-container price-final_price tax weee" buscar con la data-price-type "finalPrice"
                    
                    $precioDirty = explode("€", $precioNode);
                    $precioClean = trim($precioDirty[0]);

                    $precioFormat = str_replace(',','.',$precioClean);
                    $precio = floatval($precioFormat);
                    echo "<br> Precio: " . $precio . "<br>";
                    $valoracion = 0;
                    $idPagina = 1;

                    // $product = $this->createProducto($img, $marca, $precio, $nombre, $subcategoria, $descripcion, $valoracion, $idPagina );
                    // $product = $this->createPrecios($idProducto ,$idPagina, $precio);

                }

            }); 

        }



    }

    public function createProductos($img ,$marca, $precio, $nombre, $subcategoria, $descripcion, $valoracion, $idPagina )
    {
        Productos::create([
            "imagen" => $img,
            "nombre" => $nombre,
            "marca" => $marca,
            'id_subcategoria' => $subcategoria,
            "descripcion" => $descripcion,
            'valoracion'=> 0,
            'id_pagina'=> $idPagina
        ]);
    }

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
        $crawler = $client->request('GET', 'https://ayuda.druni.es/hc/es/articles/360012996559-Gastos-y-m%C3%A9todos-de-env%C3%ADo-');
        $this->extractShippingCostsFrom($crawler,);
    }

    public function extractShippingCostsFrom(Crawler $crawler)
    {

        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"container"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($dataNode) {


            // Filtramos el contenedor para recoger una información especifica
            $divs = $dataNode->filter("[class='article-body']")->first();
            // Gastos minimos 
            $sonsDiv = $divs->children();
            $pText = $sonsDiv->eq(1)->text();
            $pUnclean = explode("a ", $pText);
            $pUcleanArray = explode("€", $pUnclean[1]);

            $gastosMinimos =  intval($pUcleanArray[0]);

            // Para recoger los gastos de envio
            $ulNode = $divs->children()->filter('ul');
            $li = $ulNode->eq(0);
            $segundoUlNode = $ulNode->children()->filter('ul');
            $lis = $segundoUlNode->children()->filter('li');

            // Gastos de envio en peninsula
            $gastosPeninsulaText = $lis->eq(0)->text();
            $gastosPeninsulaArray = explode(" ", $gastosPeninsulaText);
            $gastosPeninsulaString = $gastosPeninsulaArray[1];
            $gastosPeninsulaSymbol = str_replace('€','',$gastosPeninsulaString);
            $gastosPeninsulaClean = str_replace(',','.',$gastosPeninsulaSymbol);

            $gastosPeninsula = floatval($gastosPeninsulaClean);

            // Gastos de envio en baleares
            $gastosBalearesText = $lis->eq(1)->text();
            $gastosBalearesArray = explode(" ", $gastosBalearesText);
            $gastosBalearesString = $gastosBalearesArray[1];
            $gastosBalearesSymbol = str_replace('€','',$gastosBalearesString);
            $gastosBalearesClean = str_replace(',','.',$gastosBalearesSymbol);
           
            $gastosBaleares = floatval($gastosBalearesClean);


            //Nombre de la tienda
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
    
    
}
