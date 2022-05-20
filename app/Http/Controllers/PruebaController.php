<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Goutte\Client;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Producto;
use App\Models\Tienda;
use App\Models\PaginaExterna;
use App\Models\Categoria;
use App\Models\Subcategoria;
use App\Models\Precio;
use Illuminate\Support\Facades\Redirect;

class PruebaController extends Controller
{

    public function hola()
    {
        dd("hola");
    }
    
    private $nombreTienda = "druni";
    private $url = "https://www.druni.es/";
    private $id_tienda = 0;


    private $limiteArticulos = 1;
    private $lastPage = 1;
    private $itemsXPage = 24;



    // RECOGER DATOS DE GASTOS DE ENVIO DE LA PÁGINA
    public function shippingCostData(Client $client)
    {
        // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
        $crawler = $client->request('GET', 'https://ayuda.druni.es/hc/es/articles/360012996559-Gastos-y-metodos-de-envio-');
        $this->extractShippingCostsFrom($crawler,);

    }

    public function extractShippingCostsFrom(Crawler $crawler)
    {

        $this->crearTienda("David" ,4.44, 5.55, 20 );
        return redirect('/')->with('success', 'Tienda creada correctamente');        
        
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"article-info"';

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


            // Añadir en la base de datos la información sobre la tienda y la pagina web
            $this->crearTienda($nombreTienda ,$gastosPeninsula, $gastosBaleares, $gastosMinimos );

            // Recoger Id de la tienda
            $id_tienda = $this->recogerIdTienda();

            $this->crearPaginaExterna();
            
            $client = new Client();
            $product = $this->pageDate($client);

        }); 

    }



    public function pageDate(Client $client)
    {
        // Recoger atributos
        $itemLimit = $this->limiteArticulos;
        $ultimaPagina = $this->lastPage;
        $articulosXPagina = $this->itemsXPage;

        // Filtramos el objeto para "crawler" para obtener la cantidad de articulos que hay
        $categorias = Categoria::all();
        foreach ($categorias as $categoria) 
        {
            $nombreCategoria = $categoria->nombre;

            $subcategorias = Subcategoria::all();
            foreach ($subcategorias as $subcategoria) 
            {
                $nombreSubcategoria = $subcategoria->nombre;
                // dd($nombreCategoria, $nombreSubcategoria);
                $pageUrl = "https://www.druni.es/maquillaje/{$nombreCategoria}/{$nombreSubcategoria}";

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

                $this->extractProductsFrom($crawler, $client ,$nombreSubcategoria);
            }
        }

    }


    public function extractProductsFrom(Crawler $crawler,Client $client ,$nombreSubcategoria)
    {
        $ultPagina =  $this->lastPage;
        for ($i = 1; $i<=$ultPagina; $i++)
        {
            $pageUrl = "https://www.druni.es/maquillaje/{$nombreSubcategoria}?p={$i}";

            // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
            $crawler = $client->request('GET', $pageUrl);

            // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
            // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
            $inlineProductStyles = '"item product product-item"';

            // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
            // con EACH iteramos cada nodo del objeto CRAWLER
            $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode, $nombreSubcategoria) {


                // Comprovar si no esta agotado
                $divAgotado = $productNode->filter("[class='product-item-inner']");
                $estaAgotado = $divAgotado->filter("[class='stock unavailable']")->count();


                if($estaAgotado == 0)
                {
                    // Filtramos el contenedor para recoger una información especifica
                    $img = $productNode->filter("[class='product-image-photo']")->first()->attr('src');

                    $nombreProducto = $productNode->filter("[class='product-item-link']")->first()->text();
                    
                    $marca = $productNode->filter("[class='product-brand']")->first()->text();
                    $descripcion = $productNode->filter("[class='product description product-item-description']")->first()->text();
                    $precioNode = $productNode->filter("[data-price-type='finalPrice']")->first()->text();
                    
                    // Si no tiene el producto un contenedor con la clase "price-container price-final_price tax weee" buscar con la data-price-type "finalPrice"
                    $precioDirty = explode("€", $precioNode);
                    $precioClean = trim($precioDirty[0]);

                    $precioFormat = str_replace(',','.',$precioClean);
                    $precio = floatval($precioFormat);


                    $this->crearProducto($img , $nombreProducto , $marca, $descripcion );
                    
                    // Recoger id producto
                    $id_producto = $this->recogerIdProducto($nombreProducto);

                    $this->crearPrecio($id_producto, $precio);
                }
            }); 
        }
    }

    // IdTienda
    public function recogerIdTienda()
    {
        //Nombre de la tienda
        $nombreTienda = $this->nombreTienda;

        $tiendas = Tienda::all();
        foreach ($tiendas as $tienda)
        {
            if ($tienda->nombre == $nombreTienda)
            {
                return $tienda->id;
            }
            // else{
            //     return redirect()->route('index')->with('error', 'Error searching store id');            
            // }
        }   
    }

    
    // IdSubcategoria
    public function recogerIdSubcategoria($nombreSubcategoria)
    {
        $this->recogerIdTienda();
        $subcategorias = Subcategoria::all();
        foreach ($subcategorias as $subcategoria)
        {
            if ($subcategoria->nombre == $nombreSubcategoria)
            {
                return $subcategoria->id;
            }
            // else{
            //     return redirect()->route('/')->with('error', 'Error searching category id');            
            // }
        }
    }

    // IdProducto
    public function recogerIdProducto($nombreProducto)
    {
        $productos = Producto::all();
        foreach ($productos as $producto)
        {
            if ($producto->nombre == $nombreProducto)
            {
                return $producto->id;
            }
            // else{
            //     return redirect()->route('/')->with('error', 'Error searching product id');            
            // }
        }
    }

    /**
     *
     * @return Response
     */
    // Crear tienda
    public function crearTienda( )
    {
        // $createDB = Tienda::create([
        //     "nombre" => $nombreTienda,
        //     "gastos_peninsula" => $gastosPeninsula,
        //     "gastos_baleares" => $gastosBaleares,
        //     'gastos_minimos' => $gastosMinimos,
        // ]);
        return "Hola";
    }

    // Crear pagina externa 
    public function crearPaginaExterna()
    {
        //Url de la tienda
        $url = $this->url;
        //Url de la tienda
        $id_tienda = $this->id_tienda;

        $createDB = PaginaExterna::create([
            "url" => $url,
            "id_tienda" => $id_tienda,
        ]);
        // if ($createDB)
        // {
        //     return redirect()->route('/')->with('success', 'Tienda created successfully.');

        // }
        // else{
        //     return redirect()->route('/')->with('error', 'Error creating PaginaExterna');            
        // }
    }
    
    // Crear producto 
    public function crearProducto($img , $nombreProducto , $marca , $descripcion, $valoracion )
    {
        // Recoger id subcategoria
        $id_subcategoria = $this->recogerIdSubcategoria($subcategoria);
        // Recoger id de la pagina
        $id_pagina = $this->id_pagina;
        $valoracion = 0;

        $createDB = Producto::create([
            "imagen" => $img,
            "nombre" => $nombreProducto,
            "marca" => $marca,
            'id_subcategoria' => $id_subcategoria,
            "descripcion" => $descripcion,
            'valoracion'=> $valoracion,
            'id_pagina'=> $id_pagina
        ]);
        // if ($createDB)
        // {
        //     return redirect()->route('/')->with('success', 'Tienda created successfully.');

        // }
        // else{
        //     return redirect()->route('/')->with('error', 'Error creating Producto');            
        // }
    }

    // Crear precios 
    public function crearPrecio($id_producto , $precio)
    {
        // Recoger id de la pagina
        $id_pagina = $this->id_pagina;

        $createDB = Precio::create([
            "id_producto" => $id_producto,
            'id_pagina'=> $id_pagina,
            "precio" => $precio,
        ]);
        // if ($createDB)
        // {
        //     return redirect()->route('/')->with('success', 'Tienda created successfully.');

        // }
        // else{
        //     return redirect()->route('/')->with('error', 'Error creating Precio');            
        // }
    }
}
