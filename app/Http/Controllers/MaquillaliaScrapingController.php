<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Producto;

class MaquillaliaScrapingController extends Controller
{
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
}
