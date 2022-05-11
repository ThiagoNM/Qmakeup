<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\CategoriaScraping;
use Illuminate\Http\Request;

class DruniCategoriaScrapingController extends Controller
{

    private $pageUrl = "https://www.druni.es";
    
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
        $inlineProductStyles = '"ui-menu-dropdown depth-1"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($categoryNode) {
            // Recogemos todas las 'a' 
            $a = $categoryNode->filter('a')->text();
            // Nombre de la categoria sin espacios
            $categoria = trim($a);

            // Recogemos las categorias que tenemos de la tabla CATEGORIAS
            // Categorias::all();
            $ListCategory = ['Rostro', 'Ojos', 'Labios'];
            $countListCat = count($ListCategory);

            // Comprovamos que es la categoria que nos interesa
            for ($i = 0; $i<$countListCat; $i++)
            {
                if ($categoria == $ListCategory[$i])
                {
                    echo("Categoria: " . $categoria . "<br>");

                    // Recogemos la ruta de la categoria
                    $ruta_categoria = $categoryNode->filter('a')->attr('href');
                    echo  "Ruta: " . $ruta_categoria . "<br>";

                    // Volvemos a filtrar la página para obtener las subclases 
                    $pageUrl = $this->pageUrl;
                    $client = new Client();

                    $inlineProductStyles = '"menuitem"';

                    $categoryNode->filter("[role=$inlineProductStyles]")->each(function($categoryNode) {
                        // Nombre de la subcategoria sin espacios
                        $subCategoria = trim($categoryNode->text());
                        echo "Subcategoria: " . $subCategoria . "<br>";

                        $ListCategory = ['Rostro', 'Ojos', 'Labios'];
                        $countListCat = count($ListCategory);
                        
                        for ($i = 0; $i<$countListCat; $i++)
                        {   
                            // Comprovamos que no recojamos una categoria
                            if (!in_array($subCategoria, $ListCategory))
                            {
                                echo "-----------ENTRA!!--------------------" . "<br>";
                                // Ruda de la subcategoria
                                $ruta_subcategoria = $categoryNode->attr('href');
                                echo "Ruta subcategoria: " . $ruta_subcategoria . "<br>";
                                echo "<br>";
                                break;
                                // $this->createSubcategoriaPagina($subCategoria, $ruta_subcategoria, $idPagina );
                            }
                        }
                    });

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
    
    
    public function createSubcategoriaPagina($subCategoria, $ruta_subcategoria, $idPagina)
    {
        Subcategoria_pagina::create([
            "nombre" => $subCategoria,
            'ruta_subcategoria' => $ruta_subcategoria,
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
