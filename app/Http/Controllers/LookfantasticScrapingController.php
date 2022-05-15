<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Producto;
use App\Models\Tienda;
use App\Models\CategoriaTienda;
use App\Models\SubcategoriaTienda;
use App\Models\PaginaExterna;
use App\Models\Precio;


class LookfantasticScrapingController extends Controller
{
    private $nombreTienda = "lookfantastic";
    private $url = "https://www.lookfantastic.es/";
    private $urlSubcategoria = "";

    // Gastos de envio y minimos
    private $gastosPeninsula = 0;
    private $gastosBaleares = 0;
    private $gastosMinimos= 0;

    public function productsCategory(Client $client)
    {
        $subcategorias = $this->recogerSubcategoriaTienda();
        foreach ($subcategorias as $subcategoria) {

            $urlSubcategoria = $subcategoria->url_subcategoria;
            $this->urlSubcategoria = $urlSubcategoria;
            $pageUrl = "$urlSubcategoria";

            $crawler = $client->request('GET', $pageUrl);

            $lastPage = $this->extractlastPage($crawler);
            for ($i = 0; $i<=$lastPage[0]; $i++)
            {
                $pageUrl = $urlSubcategoria . "?pageNumber={$i}";
            
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

                $nombreProducto = $productNode->filter("[class='productBlock_productName']")->first()->text();
                $id_producto = $this->recogerIdProducto($nombreProducto);
                if ($id_producto != null)
                {
                    // Filtramos el contenedor para recoger una información especifica
                    $precioNode = $productNode->filter("[class='productBlock_price']")->first()->text();
                    
                    $precioDirty = explode("€", $precioNode);
                    $precioClean = trim($precioDirty[0]);

                    $precioFormat = str_replace(',','.',$precioClean);
                    $precio = floatval($precioFormat);

                    dd("entra");
                    $this->crearPrecios($id_producto, $precio);
                }

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

    // Recogemos la información sobre los gastos de envio
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
                $nombreTienda = $this->nombreTienda;
                $gastosPeninsula = $this->gastosPeninsula;
                $gastosBaleares = $this->gastosBaleares;
                $gastosMinimos = $this->gastosMinimos;

                $this->crearTiendas($gastosPeninsula, $gastosBaleares, $gastosMinimos );
                $this->crearPaginasExternas();
                $cont ++;
            }
        }); 
    }



    // IdProducto
    public function recogerIdProducto($nombreProducto)
    {
        $productos = Producto::all();
        foreach ($productos as $producto)
        {
            $NameProduct = $producto->nombre;
            similar_text($nombreProducto, $NameProduct, $porciento);
            echo "<br> PORCENTAJE: ". $porciento;
            if ($porciento > 60)
            {
                dd("entra");
                return $producto->id;
            }
            // else{
            //     return redirect()->route('/')->with('error', 'Error searching product id');            
            // }
        }
    }

    public function recogerSubcategoriaTienda()
    {
        $id_tienda = $this->recogerIdTienda();
        $subcategorias = SubcategoriaTienda::all()->where('id_tienda', '=',$id_tienda);
        return $subcategorias;
    }

    
    // Id de la tienda en la que estamos
    public function recogerIdTienda()
    {
        $nombreTienda = $this->nombreTienda;
        $tienda = Tienda::all()->where('nombre', '=',$nombreTienda)->first();
        $id_tienda = $tienda->id;
        return $id_tienda ;
    }

    // Crear tienda
    public function crearTienda($gastosPeninsula, $gastosBaleares, $gastosMinimos )
    {
        $nombreTienda = $this->nombreTienda;
        $createDB = Tienda::create([
            "nombre" => $nombreTienda,
            "gastos_peninsula" => $gastosPeninsula,
            "gastos_baleares" => $gastosBaleares,
            'gastos_minimos' => $gastosMinimos,
        ]);
    }

    // Crear pagina externa 
    public function crearPaginasExternas()
    {
        try {
            //Url de la tienda
            $url = $this->url;
            //Url de la tienda
            $id_tienda = $this->recogerIdTienda();


            $createDB = PaginaExterna::create([
                "url" => $url,
                "id_tienda" => $id_tienda,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    // Guardar en la base de datos el precio
    public function crearPrecios($id_producto , $precio)
    {
        try {
            $id_tienda = $this->recogerIdTienda();
            $createDB = Precio::create([
                "id_producto" => $id_producto,
                'id_tienda'=> $id_tienda,
                "precio" => $precio,
            ]);
        } catch (\Throwable $th) {
            throw $th;
        }

    }
}
