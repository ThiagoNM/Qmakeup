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
        try {
        
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
                $this->crearTienda($gastosPeninsula, $gastosBaleares, $gastosMinimos );
                $this->crearPaginaExterna();

            }); 
        } catch (Exception $e) {
            $errors = $this->errors;
            $msg = $e->getMessage();
            array_push($errors, $msg);
            $this->errors = $errors;
        }
        if ($errors != null)
        {
            return redirect()->route('perfil')->with('success', 'Los productos y precios de la tienda Druni han sido creados correctamente.');
        }
        else{
            return redirect()->route('perfil')->with('danger', 'Los productos y precios de la tienda Druni no se han podido crear correctamente.');
        }
    }



    public function pageDate(Client $client)
    {
        set_time_limit(7200);
        $errors = [];

        try {
            // Eliminamos los productos y precios anteriores para subir a la base de datos los precios actuales
            $this->eliminarListaDeseos();
            $this->eliminarPrecios();
            $this->eliminarProductos();
        } catch (Exception $e) {
            $msg = $e->getMessage();
            // Pla A
            Log::error($msg);
            // Pla B
            array_push($errors, $msg);
        }
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
        $errors = $this->errors; 
        if ($errors != null)
        {
            return redirect()->route('perfil')->with('success', 'Los productos y precios de la tienda Druni han sido creadas correctamente.');
        }
        else{
            return redirect()->route('perfil')->with('danger', 'Los productos y precios de la tienda Druni no se han podido crear correctamente.');
        }

    }


    public function extractProductsFrom(Crawler $crawler,Client $client)
    {
        $errors = [];

        set_time_limit(300);
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
                        array_push($errors, $msg);
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
        $this->eliminarTienda();
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
        Producto::create([
            "imagen" => $img,
            "nombre" => $nombreProducto,
            "id_marca" => $id_marca,
            'id_subcategoria' => $id_subcategoria,
            "descripcion" => $descripcion,
            'valoracion'=> $valoracion,
            'id_tienda'=> $id_tienda
        ]);
    }

    // Crear precios 
    public function crearPrecio($id_producto , $precio)
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
    public function eliminarPrecios ()
    {
        $id_tienda = $this->recogerIdTienda();
        $precios = Precio::all();
        foreach($precios as $precio)
        {
            $precio->delete();
        }
    }

    // Eliminar productos  
    public function eliminarProductos ()
    {
        $id_tienda = $this->recogerIdTienda();
        $productos = Producto::all()->where("id_tienda", $id_tienda);
        if($productos->count()>0)
        {
            foreach($productos as $producto)
            {
                $producto->delete();
            }
        }
    }

    // Eliminar tienda  
    public function eliminarTienda ()
    {
        $this->eliminarListaDeseos();
        $this->eliminarPrecios();
        $this->eliminarProductos();
        $this->eliminarSubcategoriasTienda();
        $this->eliminarCategoriasTienda();

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
    public function eliminarCategoriasTienda()
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
    public function eliminarSubcategoriasTienda()
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

    // Eliminar lista de deseos  
    public function eliminarListaDeseos()
    {
        $id_tienda = $this->recogerIdTienda();
        $listasDeseos = Lista_de_deseo::all();
        if($listasDeseos->count()>0)
        {
            foreach($listasDeseos as $listasDeseo)
            {
                $listasDeseo->delete();
            }
        }
    }
}

