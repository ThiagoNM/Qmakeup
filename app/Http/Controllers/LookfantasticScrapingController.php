<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Producto;

class LookfantasticScrapingController extends Controller
{
    private $nombre = "Lookfantastic";
    private $url = "https://www.lookfantastic.es/";

    // Gastos de envio y minimos
    private $gastosPeninsula = 0;
    private $gastosBaleares = 0;
    private $gastosMinimos= 0;

    public function productsCategory(Client $client)
    {
        // Categoria::all();
        $subcategorias = ['complexion.list']; 
        foreach ($subcategorias as $subcategoria) {
            $pageUrl = "https://www.lookfantastic.es/health-beauty/make-up/{$subcategoria}";
            $crawler = $client->request('GET', $pageUrl);

            $lastPage = $this->extractlastPage($crawler);
            for ($i = 0; $i<=$lastPage[0]; $i++)
            {
                $pageUrl = "https://www.lookfantastic.es/health-beauty/make-up/{$subcategoria}?pageNumber={$i}";
            
                // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
                $crawler = $client->request('GET', $pageUrl);
                $this->extractProductsFrom($crawler);
            }
        }
    }


    public function extractProductsFrom(Crawler $crawler)
    {
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"productBlock"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode) {

            // Comprovar que tenga existencias 
            $stock = $productNode->filter("[class='productBlock_actions']")->first()->text();
            if ($stock != "PRÓXIMAMENTE")
            {

                // Filtramos el contenedor para recoger una información especifica
                $precioNode = $productNode->filter("[class='productBlock_price']")->first()->text();
                
                $precioDirty = explode("€", $precioNode);
                $precioClean = trim($precioDirty[0]);

                $precioFormat = str_replace(',','.',$precioClean);
                $precio = floatval($precioFormat);

                $product = $this->crearPaginasExternas($idProducto, $idPagina, $precio);
                $product = $this->crearPrecios($idProducto, $idPagina, $precio);

            }

        }); 


    }
    public function extractlastPage(Crawler $crawler )
    {
        // Ultima pagina

        $inlineProductStyles = '"responsiveProductListPage_topPagination"';

        $ultimaPagina = $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode) {

            $ul = $productNode->filter("[class='responsivePageSelectors']");
            $paginador = $ul->filter("[class='responsivePaginationButton responsivePageSelector   responsivePaginationButton--last']");
            $ultPagina = $paginador->text();
            return $ultPagina ;
        }); 
        return $ultimaPagina;
    }

    // RECOGER DATOS DE GASTOS DE ENVIO DE LA PÁGINA
    public function shippingCostData(Client $client)
    {
        // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
        $crawler = $client->request('GET', 'https://www.lookfantastic.es/info/delivery-information.list');
        $this->extractShippingCostsFrom($crawler,);
    }

    public function extractShippingCostsFrom(Crawler $crawler)
    {
        // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
        $inlineProductStyles = '"accordionWidget componentWidget"';

        // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
        // con EACH iteramos cada nodo del objeto CRAWLER
        $cont = 0;
        $crawler->filter("[class=$inlineProductStyles]")->each(function($dataNode, $cont) {
            if($cont < 1)
            {
                // Filtramos el contenedor para recoger una información especifica
                $divs = $dataNode->filter("[id='wrapper']")->first()->children();
                //Gastos de envio baleares, p
                $gastosPBUnclean = $divs->eq(3)->text();
                $gastosPBArrayClean = explode("€", $gastosPBUnclean);
                $gastosSubstituted = str_replace(',','.',$gastosPBArrayClean);

                $gastosPB =  floatval($gastosPBArrayClean[0]);

                $this->gastosPeninsula=$gastosPB;
                $this->gastosBaleares=$gastosPB;

                // Gastos minimos 
                $gastosMinimosUnclean = $divs->eq(1)->text();
                $gastosMinimosArrayUnclean = explode("de ", $gastosMinimosUnclean);
                $gastosMinimosArrayClean = explode("€", $gastosMinimosArrayUnclean[2]);

                $gastosMinimos =  intval($gastosMinimosArrayClean[0]);
                $this->gastosMinimos=$gastosMinimos;

                //Nombre de la tienda
                $nombre = $this->nombre;
                $gastosPeninsula = $this->gastosPeninsula;
                $gastosBaleares = $this->gastosBaleares;
                $gastosMinimos = $this->gastosMinimos;

                // $this->crearTiendas($nombre ,$gastosPeninsula, $gastosBaleares, $gastosMinimos );
                $cont ++;
            }
        }); 
    }
    // Crear tienda
    public function crearTiendas($nombre ,$gastosPeninsula, $gastosBaleares, $gastosMinimos )
    {
        Tiendas::create([
            "nombre" => $nombre,
            "gastos_peninsula" => $gastosPeninsula,
            "gastos_baleares" => $gastosBaleares,
            'gastos_minimos' => $gastosMinimos,
        ]);
    }

    // Crear pagina externa 
    public function crearPaginasExternas($url ,$id_tienda )
    {
        Tiendas::create([
            "url" => $url,
            "id_tienda" => $id_tienda,
        ]);
    }

    // Guardar en la base de datos el precio
    public function crearPrecios($id_producto ,$id_pagina, $precio)
    {
        Precios::create([
            "id_producto" => $id_producto,
            'id_pagina'=> $id_pagina,
            "precio" => $precio,
        ]);
    }
}
