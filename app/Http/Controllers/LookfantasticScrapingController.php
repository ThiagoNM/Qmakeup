<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Producto;

class LookfantasticScrapingController extends Controller
{
    public function productsCategory(Client $client)
    {
        for ($i = 0; $i<=2; $i++)
        {
            // $offset = $i ++;
            $pageUrl = "https://www.lookfantastic.es/health-beauty/make-up/eyes.list?pageNumber={$i}";
        
            // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
            $crawler = $client->request('GET', $pageUrl);
            $this->extractProductsFrom($crawler);
        }
    }


    public function extractProductsFrom(Crawler $crawler)
    {
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"productBlock"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode) {
            // Filtramos el contenedor para recoger una información especifica
            $precioNode = $productNode->filter("[class='productBlock_price']")->first()->text();
            
            $precioDirty = explode("€", $precioNode);
            $precioClean = trim($precioDirty[0]);

            $precioFormat = str_replace(',','.',$precioClean);
            $precio = floatval($precioFormat);
            
            $product = $this->extractProductInfo($idProducto, $idPagina, $precio);
        }); 


    }

    public function extractProductInfo( $idProducto, $idPagina, $precio )
    {
        Precio::create([
            "producto_id" => $idProducto,
            "pagina_id" => $idPagina,
            "precio"=> $precio
        ]);
    }
}
