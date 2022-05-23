<?php

namespace App\Http\Controllers;

use Goutte\Client;
use Illuminate\Http\Request;
use Symfony\Component\DomCrawler\Crawler;
use App\Models\Producto;
use App\Models\Tienda;
use App\Models\PaginaExterna;
use App\Models\CategoriaTienda;
use App\Models\SubcategoriaTienda;
use App\Models\Precio;
use App\Models\Marca;
use App\Models\Lista_de_deseo;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Log;

class DruniScrapingController extends Controller
{
    private $nombreTienda = "druni";
    private $url = "https://www.druni.es/";
    private $id_tienda = 0;
    private $urlSubcategoria = "";
    private $id_subcategoria = 0;
    private $url_producto = "";
    private $errors = [];

    private $limiteArticulos = 1;
    private $lastPage = 1;
    private $itemsXPage = 24;

    // RECOGER DATOS DE GASTOS DE ENVIO DE LA PÁGINA
    public function shippingCostData(Client $client)
    {
        // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
        $crawler = $client->request('GET', 'https://ayuda.druni.es/hc/es/articles/360012996559-Gastos-y-metodos-de-envio-');
        $this->extractShippingCostsFrom($crawler);
        $errors = $this->errors;
        if ($errors == [])
        {
            return redirect()->route('perfil')->with('success', 'La tienda Druni ha sido creada correctamente.');
        }
        else{
            return redirect()->route('perfil')->with('danger', 'La tienda Druni no se ha podido crear correctamente.');
        }
    }

    public function extractShippingCostsFrom(Crawler $crawler)
    {
        try {
            // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
            $inlineProductStyles = '"article-info"';
            // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
            // con EACH iteramos cada nodo del objeto CRAWLER
            $entraCrawler = $crawler->filter("[class=$inlineProductStyles]")->each(function($dataNode) {
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
                $this->crearTienda($gastosPeninsula, $gastosBaleares, $gastosMinimos );
                $this->crearPaginaExterna();
                return True;
            }); 
            if (!$entraCrawler)
            {
                $errors = $this->errors;
                array_push($errors, "No entra en el crawler.");
                $this->errors = $errors;
            }
        } catch (Exception $e) {
            $errors = $this->errors;
            $msg = $e->getMessage();
            array_push($errors, $msg);
            $this->errors = $errors;
        }
        
    }


    public function pageDate(Client $client)
    {
        set_time_limit(5200);
        // Recoger atributos
        $itemLimit = $this->limiteArticulos;
        $ultimaPagina = $this->lastPage;
        $articulosXPagina = $this->itemsXPage;

        // Filtramos el objeto para "crawler" para obtener la cantidad de articulos que hay

        $subcategorias = $this->recogerSubcategoriaTienda();

        foreach ($subcategorias as $subcategoria) 
        {
            $urlSubcategoria = $subcategoria->url_subcategoria;
            $this->urlSubcategoria = $urlSubcategoria;
            $pageUrl = "$urlSubcategoria";

            // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
            $crawler = $client->request('GET', $pageUrl);

            $inlineArtId = '"maincontent"';
            $limiteArt = $crawler->filter("[id=$inlineArtId]")->each(function($NumNode) {
                // Filtramos el contenedor para recoger una información especifica
                $limiteArt = $NumNode->filter("[class='toolbar-amount']")->first()->text();
                return $limiteArt;
            }); 

            $limiteArt = intval($limiteArt[0]);
            $this->limiteArticulos = $limiteArt;

            $ultPagina = $limiteArt / $articulosXPagina;
            $ultPagina = intval(ceil($ultPagina));
            $this->lastPage = $ultPagina;
            $this->extractProductsFrom($crawler, $client);
        }
        // Termina
        $errors = $this->errors; 
        if ($errors == [])
        {
            return redirect()->route('perfil')->with('success', 'Los productos y precios de Druni han sido creados correctamente.');
        }
        else{
            return redirect()->route('perfil')->with('danger', 'Los productos y precios de Druni no se han creado correctamente.');
        }

    }


    public function extractProductsFrom(Crawler $crawler,Client $client)
    {

        $ultPagina =  $this->lastPage;

        for ($i = 1; $i<=$ultPagina; $i++)
        {

            $urlSubcategoria = $this->urlSubcategoria;

            $pageUrl = $urlSubcategoria . "?p={$i}";
            
            $this->id_subcategoria = $this->recogerIdSubcategoria();

            // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
            $crawler = $client->request('GET', $pageUrl);
            // Hacemos una peticion a la página y nos devuebe un objetp CRAWLER para analizar el contenido de la página web
            // Filtrar todos los elementos que contengan como clase que que contega la variable $inlineContactStyles
            $inlineProductStyles = '"item product product-item"';

            // Filtramos el objeto CRAWLER para obtener el contenedor con toda la información
            // con EACH iteramos cada nodo del objeto CRAWLER
            $crawler->filter("[class=$inlineProductStyles]")->each(function($productNode) {
            

                // Comprovar si no esta agotado
                $divAgotado = $productNode->filter("[class='product-item-inner']");
                $estaAgotado = $divAgotado->filter("[class='stock unavailable']")->count();

                if($estaAgotado == 0)
                {

                    // Filtramos el contenedor para recoger una información especifica
                    $img = $productNode->filter("[class='product-image-photo']")->first()->attr('src');

                    $nombreProducto = $productNode->filter("[class='product-item-link']")->first()->text();
                    
                    $url_producto = $productNode->filter("[class='product photo product-item-photo']")->first()->attr('href');
                    $this->url_producto = $url_producto;
                    
                    $marca = $productNode->filter("[class='product-brand']")->first()->text();
                    $descripcion = $productNode->filter("[class='product description product-item-description']")->first()->text();
                    $precioNode = $productNode->filter("[data-price-type='finalPrice']")->attr('data-price-amount');
                    
                    // Si no tiene el producto un contenedor con la clase "price-container price-final_price tax weee" buscar con la data-price-type "finalPrice"
                    $precioDirty = explode("€", $precioNode);
                    $precioClean = trim($precioDirty[0]);

                    $precioFormat = str_replace(',','.',$precioClean);
                    $precio = floatval($precioFormat);
                    
                    try {
                        $this->crearMarca($marca);
                        $this->crearProducto($img , $nombreProducto, $marca , $descripcion);
                    
                        // Recoger id producto
                        $id_producto = $this->recogerIdProducto($nombreProducto);
                        $this->crearPrecio($id_producto, $precio);  

                    } catch (Exception $e) {
                        $msg = $e->getMessage();
                        // Pla A
                        Log::error($msg);
                        // Pla B
                        $errors = $this->errors;
                        array_push($errors, $msg);
                        $this->errors = $errors;
                    }
                }
            }); 
        }
    }


    public function recogerSubcategoriaTienda()
    {
        $id_tienda = $this->recogerIdTienda();
        $subcategorias = SubcategoriaTienda::all()->where('id_tienda',$id_tienda);
        return $subcategorias;
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
        }   
    }

    
    // IdSubcategoria
    public function recogerIdSubcategoria()
    {
        $urlSubcategoria = $this->urlSubcategoria;
        $subcategorias = $this->recogerSubcategoriaTienda();
        foreach ($subcategorias as $subcategoria)
        {

            if ($subcategoria->url_subcategoria == $urlSubcategoria)
            {
                return $subcategoria->id;
            }
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
        }
    }


    // Crear tienda
    public function crearTienda($gastosPeninsula, $gastosBaleares, $gastosMinimos )
    {
        $nombreTienda = $this->nombreTienda;
        $existeTienda = Tienda::where("nombre", $nombreTienda)->exists();
        if(!$existeTienda)
        {
            $createDB = Tienda::create([
                "nombre" => $nombreTienda,
                "gastos_peninsula" => $gastosPeninsula,
                "gastos_baleares" => $gastosBaleares,
                'gastos_minimos' => $gastosMinimos,
            ]);
        }else{
            Tienda::where("nombre", $nombreTienda)
                    ->update([ 
                        "gastos_peninsula" => $gastosPeninsula ,
                        "gastos_baleares" => $gastosBaleares, 
                        "gastos_minimos" => $gastosMinimos
                    ]);
        }
    }

    // Crear pagina externa 
    public function crearPaginaExterna()
    {
        //Url de la tienda
        $url = $this->url;
        //Url de la tienda
        $id_tienda = $this->recogerIdTienda();

        $existePaginaExterna = PaginaExterna::where("id_tienda", $id_tienda)->exists();
        if(!$existePaginaExterna){
            $createDB = PaginaExterna::create([
                "url" => $url,
                "id_tienda" => $id_tienda,
            ]);
        }else{
            PaginaExterna::where("id_tienda", $id_tienda)
                        ->update([ 
                            "url" => $url
                        ]);
        }
    }
    
    // Crear producto 
    public function crearMarca($marca)
    {
        $ExisteMarca = Marca::where("marca", $marca)->exists();
        if (!$ExisteMarca)
        {
            $marca = strtoupper($marca);
            Marca::create([
                'marca' => $marca,
            ]);
        }
    }

    // IdMarca
    public function recogerIdMarca($marca)
    {
        $id_marca = Marca::all()->where("marca", $marca)->first()->id;
        return $id_marca;
    }

    // Crear producto 
    public function crearProducto($img , $nombreProducto , $marca, $descripcion)
    {
        $id_tienda = $this->recogerIdTienda();
        $id_marca = $this->recogerIdMarca($marca);
        $id_subcategoria = $this->id_subcategoria;
        $valoracion = 0;
        $valoracion_media = 0;

        $existeProducto = Producto::where("nombre", $nombreProducto)->where("descripcion", $descripcion)->exists();
        if(!$existeProducto){
            Producto::create([
                "imagen" => $img,
                "nombre" => $nombreProducto,
                "id_marca" => $id_marca,
                'id_subcategoria' => $id_subcategoria,
                "descripcion" => $descripcion,
                'valoracion'=> $valoracion,
                'id_tienda'=> $id_tienda,
                'valoracion_media' => $valoracion_media
            ]);
        }else{
            Producto::where("nombre", $nombreProducto)->where("descripcion", $descripcion)
                    ->update([ 
                        "imagen" => $img
                    ]);
        }
    }

    // Crear precios 
    public function crearPrecio($id_producto , $precio)
    {
        $id_tienda = $this->recogerIdTienda();
        $url_producto = $this->url_producto;
        $existePrecio = Precio::where("id_producto", $id_producto)->where("id_tienda",$id_tienda)->exists();
        if(!$existePrecio){
            $createDB = Precio::create([
                "id_producto" => $id_producto,
                'id_tienda'=> $id_tienda,
                "precio" => $precio,
                "url_producto" => $url_producto
            ]);
        }else{
            Precio::where("id_producto", $id_producto)->where("id_tienda",$id_tienda)
                    ->update([ 
                        "precio" => $precio,
                        "url_producto" => $url_producto
                    ]);
        }
    }
}

