<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Producto;

class DruniScrapingController extends Controller
{
    public function productsCategory(Client $client)
    {

        // Categoria::all();
        $categorias = ['paco']; 
        foreach ($categorias as $categoria) {
            for ($i = 4999; $i<=5000; $i++)
            {
                echo "<br> Pagina: " . $i . "<br>";
                $pageUrl = "https://www.druni.es/maquillaje/{$categoria}/?p=$i";
            
                // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
                $crawler = $client->request('GET', $pageUrl);
                $inlineArtId = '"maincontent"';
                $limiteArt = $crawler->filter("[id=$inlineArtId]")->each(function($productNode) {

                    // Filtramos el contenedor para recoger una información especifica
                    $limiteArt = $productNode->filter("[class='toolbar-number']")->first()->text();
                    return $limiteArt;
                }); 
                $limiteArt = intval($limiteArt[0]);
                $this->extractProductsFrom($crawler, $categoria, $limiteArt);
            }
            

        }
    }


    public function extractProductsFrom(Crawler $crawler,  $categoria, $limiteArt)
    {
        for ($i = 0; $i<=$limiteArt; $i++)
        {
            // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
            $inlineProductStyles = '"item product product-item"';

            // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
            // con EACH iteramos cada nodo del objeto CRAWLER
            $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode, $categoria) {

                // Filtramos el contenedor para recoger una información especifica
                $img = $productNode->filter("[class='product-image-photo']")->first()->attr('src');

                $nombre = $productNode->filter("[class='product-item-link']")->first()->text();
                $marca = $productNode->filter("[class='product-brand']")->first()->text();
                $descripcion = $productNode->filter("[class='product description product-item-description']")->first()->text();
                $precioNode = $productNode->filter("[class='price-container price-final_price tax weee']")->first()->text();
                
                
                $precioDirty = explode("€", $precioNode);
                $precioClean = trim($precioDirty[0]);

                $precioFormat = str_replace(',','.',$precioClean);
                $precio = floatval($precioFormat);

                $valoracion = 0;
                $idPagina = 1;

                $product = $this->extractProductInfo($img, $marca, $precio, $nombre, $categoria, $descripcion, $valoracion, $idPagina );
            }); 
        }

    }

    public function extractProductInfo($img ,$marca, $precio, $nombre, $categoria, $descripcion, $valoracion, $idPagina )
    {
        Producto::create([
            "imagen" => $img,
            "marca" => $marca,
            "precio" => $precio,
            "nombre" => $nombre,
            'categoria' => $categoria,
            "descripcion" => $descripcion,
            'valoracion'=> 0,
            'id_pagina'=> $idPagina
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
            $ulNode = $divs->children()->filter('ul');
            $li = $ulNode->eq(0);
            $segundoUlNode = $ulNode->children()->filter('ul');
            $lis = $segundoUlNode->children()->filter('li');

            $primerLi = $lis->eq(0);
            $segundoLi = $lis->eq(1);

            
            dd($segundoLi->text());
        }); 
    }

    
}
