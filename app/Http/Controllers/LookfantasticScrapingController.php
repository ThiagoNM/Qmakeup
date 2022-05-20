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
use App\Models\Marca;


class LookfantasticScrapingController extends Controller
{
    private $nombreTienda = "lookfantastic";
    private $url = "https://www.lookfantastic.es/";
    private $urlSubcategoria = "";
    private $url_producto = "";
    // Gastos de envio y minimos
    private $gastosPeninsula = 0;
    private $gastosBaleares = 0;
    private $gastosMinimos= 0;
    private $errors = [];


    public function productsCategory(Client $client)
    {
        set_time_limit(7200);
        try {
            // Eliminamos los precios anteriores para subir a la base de datos los precios actuales
            $this->EliminarPrecios();
        } catch (Exception $e) {
            $errors = $this->errors;
            $msg = $e->getMessage();
            array_push($errors, $msg);
            $this->errors = $errors;
        }


        $subcategorias = $this->recogerSubcategoriaTienda();
        foreach ($subcategorias as $subcategoria) {

            $urlSubcategoria = $subcategoria->url_subcategoria;
            $this->urlSubcategoria = $urlSubcategoria;
            $url = "$urlSubcategoria";
            $crawler = $client->request('GET', $url);

            $lastPage = $this->extractlastPage($crawler);
            for ($i = 1; $i<=$lastPage[0]; $i++)
            {
                $pageUrl = $url . "?pageNumber={$i}";
                echo "<br> URL: " . $pageUrl;
            
                // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
                $crawler = $client->request('GET', $pageUrl);
                $this->extractProductsFrom($crawler);
            }
        }
        echo "Ya estan todos los productos de Look recogidos";
        echo "<h2>Errors:</h2>";
        $errors = $this->errors;

        foreach($errors as $msg) {
            echo "<pre>{$msg}</pre>";
        }
        if ($errors != null)
        {
            return view('perfil')->with('success', 'Las categorias y subcategorias de la tienda Lookfantastic han sido creadas correctamente.');
        }
        else{
            return view('perfil')->with('error', 'Las categorias y subcategorias de la tienda Lookfantastic no se han podido crear correctamente.');
        }
    }


    public function extractProductsFrom(Crawler $crawler)
    {

        set_time_limit(300);
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
                
                // -------------------------------------------
                $linkHome = "https://www.lookfantastic.es";
                $linkNode = $productNode->filter("[class='productBlock_link']")->first()->attr('href');
                $url_producto = $linkHome . $linkNode;
                $this->url_producto = $url_producto;
                echo "<br> ---------------- URL PRODUCTO: ". $url_producto;
                $client = new Client();
                $crawler = $client->request('GET', $url_producto);
                $marcaNode = $crawler->filter("[class= 'athenaProductPage_productDetailsContainer' ]")->each(function($productNode) {
                    $longDiv = $productNode->filter("[class='productBrandLogo_image']")->count();
                    if ($longDiv != 0)
                    {
                        $marcaN = $productNode->filter("[class='productBrandLogo_image']")->first()->attr('title');
                        return $marcaN;
                    }
                    else{
                        $marcaN = array("");
                        return $marcaN;
                    }
                });
                if (!empty($marcaNode))
                {
                    $marcaProducto = $marcaNode[0];
                    echo "<br> TIPO DE MARCA ANTES: " . gettype($marcaProducto);
                    if (gettype($marcaProducto)!="array")
                    {
                        $id_existeMarca = $this->comprovarMarca($marcaProducto);
                        $id_producto = $this->recogerIdProducto($nombreProducto, $id_existeMarca);
                        if ($id_producto != null)
                        {
                            // Filtramos el contenedor para recoger una información especifica
                            $precioNode = $productNode->filter("[class='productBlock_price']")->first()->text();
                            
                            $precioDirty = explode("€", $precioNode);
                            $precioClean = trim($precioDirty[0]);

                            $precioFormat = str_replace(',','.',$precioClean);
                            $precio = floatval($precioFormat);

                            try {
                                $this->crearPrecios($id_producto, $precio);
                                echo "<br>Precio creado";
                            } catch (Exception $e) {
                                $errors = $this->errors;
                                $msg = $e->getMessage();
                                array_push($errors, $msg);
                                $this->errors = $errors;
                            }
                    
                        } 
                    }
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

                $this->crearTienda($gastosPeninsula, $gastosBaleares, $gastosMinimos );
                $this->crearPaginaExterna();

                $cont ++;
            }
        }); 
    }

    public function comprovarMarca($marcaProducto)
    {
        echo "<br> COMPROVANDO MARCA";
        if (gettype($marcaProducto) != "array")
        {
            echo "<br> MARCA ES DE TIPO: " . gettype($marcaProducto);
            $marcaProducto = strtoupper($marcaProducto);
            echo $marcaProducto;
            similar_text($marcaProducto, "MAYBELLINE", $porciento);
            if($porciento > 80)
            {
                $id_marca = Marca::all()->where("marca", "MAYBELLINE NEW YORK")->first()->id;
                return $id_marca;
            }
            else{
                $ExisteMarcas = Marca::all()->where("marca", $marcaProducto);
                if ($ExisteMarcas->count())
                {
                    echo "Entra1";
                    foreach($ExisteMarcas as $ExisteMarca)
                    {
                        $id_existeMarca = $ExisteMarca->id;
                    }
                    echo "<br> Marca: " . $marcaProducto[0];
                    echo "<br> Id Marca: " . $id_existeMarca;
                    return $id_existeMarca;
                }
            }
        }
    }

    // IdProducto
    public function recogerIdProducto($nombreProducto, $id_existeMarca)
    {
        $productos = Producto::all()->where("id_marca", $id_existeMarca);
        foreach ($productos as $producto)
        {
            $NameProduct = $producto->nombre;
            similar_text($nombreProducto, $NameProduct, $porciento);
            echo "<br>";
            echo "<br> PORCENTAJE: ". $porciento;
            echo "<br> --- NOMBRE ENTRA: ". $nombreProducto;
            echo "<br> NOMBRE BD: ". $NameProduct;

            if ($porciento > 65)
            {
                return $producto->id;
            }
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
        $this->EliminarTienda();
        $nombreTienda = $this->nombreTienda;
        $createDB = Tienda::create([
            "nombre" => $nombreTienda,
            "gastos_peninsula" => $gastosPeninsula,
            "gastos_baleares" => $gastosBaleares,
            'gastos_minimos' => $gastosMinimos,
        ]);
    }

    // Crear pagina externa 
    public function crearPaginaExterna()
    {
        //Url de la tienda
        $url = $this->url;
        //Url de la tienda
        $id_tienda = $this->recogerIdTienda();


        $createDB = PaginaExterna::create([
            "url" => $url,
            "id_tienda" => $id_tienda,
        ]);
    }

    // Guardar en la base de datos el precio
    public function crearPrecios($id_producto , $precio)
    {
        $id_tienda = $this->recogerIdTienda();
        $url_producto = $this->url_producto;
        $createDB = Precio::create([
            "id_producto" => $id_producto,
            'id_tienda'=> $id_tienda,
            "precio" => $precio,
            "url_producto" => $url_producto
        ]);
    }
    // Eliminar precios  
    public function EliminarPrecios ()
    {
        $id_tienda = $this->recogerIdTienda();
        $precios = Precio::all()->where("id_tienda", $id_tienda);
        foreach($precios as $precio)
        {
            $precio->delete();
        }
    }

    // Eliminar pagina externa  
    public function EliminarPaginaExterna ()
    {
        $id_tienda = $this->recogerIdTienda();
        $paginaExternas = PaginaExterna::all()->where("id_tienda", $id_tienda);
        if($paginaExternas->count()>0)
        {
            foreach($paginaExternas as $paginaExterna)
            {
                $paginaExterna->delete();
            }
        }
    }

    // Eliminar tienda  
    public function EliminarTienda ()
    {
        $this->EliminarPrecios();
        $this->EliminarSubcategoriasTienda();
        $this->EliminarCategoriasTienda();
        $this->EliminarPaginaExterna();
        $id_tienda = $this->recogerIdTienda();
        $tiendas = Tienda::all()->where("id", $id_tienda);
        if($tiendas->count()>0)
        {
            foreach($tiendas as $tienda)
            {
                $tienda->delete();
            }
        }
    }

    // Eliminar Categorias  
    public function EliminarCategoriasTienda()
    {
        $id_tienda = $this->recogerIdTienda();
        $categoriasTienda = CategoriaTienda::all()->where("id_tienda", $id_tienda);
        if($categoriasTienda->count()>0)
        {
            foreach($categoriasTienda as $categoriaTienda)
            {
                $categoriaTienda->delete();
            }
        }
    }
    
    // Eliminar Subcategorias  
    public function EliminarSubcategoriasTienda()
    {
        $id_tienda = $this->recogerIdTienda();
        $subcategoriasTienda = SubcategoriaTienda::all()->where("id_tienda", $id_tienda);
        if($subcategoriasTienda->count()>0)
        {
            foreach($subcategoriasTienda as $subcategoriaTienda)
            {
                $subcategoriaTienda->delete();
            }
        }
    }
}
