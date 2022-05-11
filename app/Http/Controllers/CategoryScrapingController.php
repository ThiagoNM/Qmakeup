<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\CategoriaScraping;
use Illuminate\Http\Request;

class CategoryScrapingController extends Controller
{

    private $crawler = '';
    
    public function category(Client $client)
    {
        $pageUrl = "https://www.lookfantastic.es/";

        // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
        $crawler = $client->request('GET', $pageUrl);
        $this->crawler = $crawler;

        $this->extractCategoryFrom($crawler);
    }


    public function extractCategoryFrom(Crawler $crawler)
    {

        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"subnav-level-three"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[data-subnav-level=$inlineProductStyles]")->each(function($categoryNode) {
            $a = $categoryNode->filter("[class='responsiveFlyoutMenu_levelThreeLink']");
            $subCategoria = $a->filter("[class='responsiveFlyoutMenu_levelThreeLinkText']")->text();
            
            echo $subCategoria . "<br>";



            // $divs = $productNode->children()->filter('div');
            // $priceNode = $divs->eq(4);
            // $precioString = $priceNode->attr('data-price');
            // $precio = floatval($precioString);
            // dd($precio);
        });


    }

    public function extractSubcategory($categoria)
    {
        $crawler = $this->crawler;
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"responsiveFlyoutMenu_levelTwoItem"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($categoryNode) {
            // SUBCATEGORIA
            $spansCat = $categoryNode->filter("[class='responsiveFlyoutMenu_levelThree']");
            $subCategoria = $spansCat->filter("[class='responsiveFlyoutMenu_levelThreeLinkText']");
            $subCategoria =  $subCategoria->text();

            echo "<br>". $subCategoria;

        // $product = $this->createCategoriaTienda($categoria, $ruta_categoria, $idPagina );
    });
    }


    public function createCategoriaPagina($categoria, $ruta_categoria, $idPagina)
    {
        Categoria_pagina::create([
            "nombre" => $categoria,
            'ruta_categoria' => $ruta_categoria,
            'id_pagina'=> $idPagina
        ]);
    }
    


    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function show(Categoria $categoria)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function edit(Categoria $categoria)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Categoria $categoria)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Categoria  $categoria
     * @return \Illuminate\Http\Response
     */
    public function destroy(Categoria $categoria)
    {
        //
    }
}
