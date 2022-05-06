<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Producto;

class PrimorScrapingController extends Controller
{
    public function productsCategory(Client $client)
    {
        // Categoria::all();
        $categorias = ['ojos', 'rostro', 'unas', 'labios']; 
        // foreach ($categorias as $cat) {
            for ($i = 0; $i<=13; $i++)
            {
                echo "<br> Pagina: " . $i . "<br>";
                $pageUrl = "https://www.primor.eu/6-labios";
            
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
            

        // }
    }


    public function extractProductsFrom(Crawler $crawler,  $limiteArt)
    {
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"product-container "';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode) {
   
            $divs = $productNode->children()->filter('div');
            $sectionInfo = $divs->eq(1);
            $aNode = $sectionInfo->children()->filter('a');

            $imgNode = $aNode->children()->filter('img')->attr('data-src');
            $nameNode = $productNode->filter("[class='product-name']")->first()->text();
            echo "Nombre: " . $nameNode;
            $marcaNode = $productNode->filter("[class='product-manufacturer']")->first()->text();

            $precioNode = $productNode->filter("[class='content_price price-box clearfix']")->filter('span');
            $sectionInfo = $precioNode->eq(2);
            $textInfo = $sectionInfo->text();

            $precioNode = explode("€", $textInfo);
            $precioNode = floatval($precioNode[0]);
            // dd($precioNode);

            // -------------------------------------------
            $link = $aNode->attr('href');

            $client = new Client();
            $crawler = $client->request('GET', $link);

            $descripcionNode = $crawler->filter("[class= 'pb-center-column span7' ]")->each(function($productNode) {
                echo "Dentro!!!";
                $divs = $productNode->children()->filter('div');
                $primerDiv = $divs->eq(1);

                $formNode = $primerDiv->children()->filter('form');
                $segundoDiv = $formNode->children()->filter("[class='rte']");

                $descripcionN = $segundoDiv->text();

                return $descripcionN;

            });

            // dd("Imagen: " . $imgNode . "Marca: " . $marcaNode . "Precio: " . $precioNode .  "Nombre: " . $nameNode . "Categoria: "  .  "Descripcion: " . $descripcionNode[0]);
            // $product = $this->extractProductInfo($imgNode ,$marcaNode, $precioNode, $nameNode, $categoria, $descripcionNode, $valoracion, $idPagina );
        }); 


    }

    public function extractProductInfo($imgNode ,$marcaNode, $precioNode, $nameNode, $categoria, $descripcionNode, $valoracion, $idPagina )
    {
        // Producto::create([
        //     "imagen" => $imgNode,
        //     "marca" => $marcaNode,
        //     "precio" => $precioNode,
        //     "nombre" => $nameNode,
        //     'categoria' => $categoria,
        //     "descripcion" => $descripcionNode,
        //     'valoracion'=> 0,
        //     'id_pagina'=> $idPagina
        // ]);
    }
    
}
