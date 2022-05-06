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
        // Categoria::all();
        // $categorias = ['ojos', 'rostro', 'unas', 'labios']; 
        // foreach ($categorias as $categoria) {
        //     for ($i = 0; $i<=2; $i++)
        //     {
                // $offset = $i ++;
                $pageUrl = "https://www.lookfantastic.es/health-beauty/make-up/eyes.list";
            
                // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
                $crawler = $client->request('GET', $pageUrl);
                $this->extractProductsFrom($crawler);
            // }
            

        // }
    }


    public function extractProductsFrom(Crawler $crawler)
    {
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"productBlock"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode) {
            // Filtramos el contenedor para recoger una información especifica
            $imgNode = $productNode->filter("[class='productBlock_image']")->first()->attr('src');
            echo "Imagen: " . $imgNode . "<br>" ;
            
            $nameNode = $productNode->filter("[class='productBlock_productName']")->first()->text();
            $precioNode = $productNode->filter("[class='productBlock_price']")->first()->text();
            $precioNode = explode("€", $precioNode);
            $precioNode = intval($precioNode[0]);
            echo "Nombre: " . $nameNode . "<br>" ;
            echo "Precio: " . $precioNode . "<br>" ;


            $linkHome = "https://www.lookfantastic.es";
            $linkNode = $productNode->filter("[class='productBlock_link']")->first()->attr('href');
            $link = $linkHome . $linkNode;
            echo "Link: " . $link . "<br>" ;
            
            // -------------------------------------------

            $client = new Client();
            $crawler = $client->request('GET', $link);
            $marcaNode = $crawler->filter("[class= 'athenaProductPage_productDetailsContainer' ]")->each(function($productNode) {
                
                $longDiv = $productNode->filter("[class='productBrandLogo_image']")->count();
                if ($longDiv != 0)
                {
                    $marcaN = $productNode->filter("[class='productBrandLogo_image']")->first()->attr('title');
                    return $marcaN;
                }
                else{
                    $marcaN = array("si");
                    return $marcaN;
                }
            });
            // echo "Marca: " . implode($marcaNode) . "<br>" ;

            // $marcaNode = implode($marcaNode);

            $descripcionNode = $crawler->filter("[class= 'athenaProductPage_breakpoint-lg_productDescription' ]")->each(function($productNode) {
                
                $descripcionN = $productNode->filter("[class='productDescription_synopsisContent']")->text();
                return $descripcionN;
            });
            echo "Descripción: " . $descripcionNode[0] . "<br>" ;

            $product = $this->extractProductInfo($imgNode ,$marcaNode, $precioNode, $nameNode, $categoria, $descripcionNode, $valoracion, $idPagina );
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
