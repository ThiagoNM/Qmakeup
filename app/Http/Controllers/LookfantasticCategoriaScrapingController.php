<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\CategoriaScraping;
use Illuminate\Http\Request;

class LookfantasticCategoriaScrapingController extends Controller
{
    // URL de la página
    private $pageUrl = "https://www.lookfantastic.es";
    
    // Para hacer una solicitud a la página
    public function category(Client $client)
    {
        $pageUrl = $this->pageUrl;

        // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
        $crawler = $client->request('GET', $pageUrl);
        $this->extractCategoryFrom($crawler);
    }


    public function extractCategoryFrom(Crawler $crawler)
    {

        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"responsiveFlyoutMenu_levelTwoItem"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($categoryNode) {

            // Recogemos las categorias que tenemos de la tabla CATEGORIAS
            // Categorias::all();
            $ListCategory = ['Rostro', 'Ojos', 'Labios'];
            $countListCat = count($ListCategory);

            $uls = $categoryNode->filter('span');
            $ulNode = $uls->filter("[class='responsiveFlyoutMenu_levelTwoLinkText']");
            $categoriaHTML = $ulNode->html();
            // Nombre de la categoria sin espacios
            $categoria = trim($categoriaHTML);

            // Comprovamos que es la categoria que nos interesa
            for ($i = 0; $i<$countListCat; $i++)
            {
                if ($categoria == $ListCategory[$i])
                {
                    // Recogemos la ruta de la categoria
                    $ruta_categoria = $categoryNode->filter('a')->attr('href');
                    
                    // Volvemos a filtrar la página para obtener las subclases 
                    $pageUrl = $this->pageUrl;
                    $client = new Client();
                    
                    echo "<br>" . "Categoria: " . $categoria . "<br>";
                    // Creamos una categoria en la base de datos en la tabla CATEGORIA_PÁGINA
                    // $product = $this->createCategoriaPagina($categoria, $ruta_categoria, $idPagina );

                    $inlineProductStyles = '"subnav-level-three"';

                    // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
                    // con EACH iteramos cada nodo del objeto CRAWLER
                    $categoryNode->filter("[data-subnav-level=$inlineProductStyles]")->each(function($categoryNode) {
                        // Recogemos la ruta de la subcategoria
                        $r_subcategoria = $categoryNode->filter("[class='responsiveFlyoutMenu_levelThreeLink']");
                        
                        // Juntamos la URL de la pagina más la ruta de la subcategoria para poder ontenerla entera y despues poder recoger los poductos de esa subcategoria.
                        $pageUrl = $this->pageUrl;
                        $ruta_categoria = $pageUrl . $r_subcategoria->attr('href');

                        // Nombre de la subcategoria
                        $subCategoria = $r_subcategoria->filter("[class='responsiveFlyoutMenu_levelThreeLinkText']")->text();
                        echo "<br>" . "Subcategoria: " . $subCategoria. "<br>";
                    });
                    // $product = $this->createSubcategoriaPagina($categoria, $ruta_subcategoria, $idPagina );

                }
            }
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
    
    
    public function createSubcategoriaPagina($categoria, $ruta_categoria, $idPagina)
    {
        Subcategoria_pagina::create([
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
