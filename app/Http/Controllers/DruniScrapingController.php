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
        $categorias = ['ojos', 'rostro', 'unas', 'labios']; 
        foreach ($categorias as $cat) {
            for ($i = 0; $i<=2; $i++)
            {
                echo "<br> Pagina: " . $i . "<br>";
                $offset = $i ++;
                $pageUrl = "https://www.druni.es/maquillaje/{$cat}/?p=$offset";
            
                // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
                $crawler = $client->request('GET', $pageUrl);

                $inlineArtId = '"maincontent"';
                $limiteArt = $crawler->filter("[id=$inlineArtId]")->each(function($productNode) {

                    // Filtramos el contenedor para recoger una información especifica
                    $limiteArt = $productNode->filter("[class='toolbar-number']")->first()->text();
                    return $limiteArt;
                }); 


                $this->extractProductsFrom($crawler, $limiteArt);
            }
            

        }
    }


    public function extractProductsFrom(Crawler $crawler,  $limiteArt)
    {
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"item product product-item"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode) {

            if ($productNode != null)
            {

                // Filtramos el contenedor para recoger una información especifica
                $imgNode = $productNode->filter("[class='product-image-photo']")->first()->attr('src');

                $nameNode = $productNode->filter("[class='product-item-link']")->first()->text();
                $marcaNode = $productNode->filter("[class='product-brand']")->first()->text();
                $descripcionNode = $productNode->filter("[class='product description product-item-description']")->first()->text();
                $precioNode = $productNode->filter("[class='price-container price-final_price tax weee']")->first()->text();
                
                $precioNode = explode("€", $precioNode);
                $precioNode = intval($precioNode[0]);
                
                $product = $this->extractProductInfo($imgNode ,$nameNode, $marcaNode, $descripcionNode, $precioNode);
            
            }
            else{
                echo "<br> Bien";
            }

        }); 
    }

    public function extractProductInfo($imgNode ,$nameNode, $marcaNode, $descripcionNode, $precioNode)
    {
        echo $imgNode . "<br>" . $nameNode . "<br>" . $marcaNode . "<br>" .$descripcionNode . "<br>";        
        // Producto::create([
        //     "marca" => $marcaNode,
        //     "precio" => $precioNode,
        //     "nombre" => $nameNode,
        //     'categoria' => "SI",
        //     "descripcion" => $descripcionNode,
        //     'valoracion'=> 0,
        //     'id_pagina'=> 1
        // ]);
    }


    // RECOGER DATOS DE LA PÁGINA
    
    public function pageData(Client $client)
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

            // $precioNode = $productNode->filter("[class='content_price price-box clearfix']")->filter('span');
            // $sectionInfo = $precioNode->eq(2);
            // $textInfo = $sectionInfo->text();

            // Filtramos el contenedor para recoger una información especifica
            $divs = $dataNode->filter("[class='article-body']")->first();
            $ulNode = $divs->children()->filter('ul');
            $li = $ulNode->eq(0);
            $segundoUlNode = $ulNode->children()->filter('ul');
            $lis = $segundoUlNode->children()->filter('li');

            $primerLi = $lis->eq(0);
            $segundoLi = $lis->eq(1);

            
            dd($segundoLi->text());



            


            $product = $this->extractProductInfo($imgNode ,$nameNode, $marcaNode, $descripcionNode, $precioNode);

        }); 
    }

    
}
