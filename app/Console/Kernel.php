<?php

namespace App\Console;

use Illuminate\Console\Scheduling\Schedule;
use Illuminate\Foundation\Console\Kernel as ConsoleKernel;

use Goutte\Client;
use App\Http\Controllers\DruniScrapingController;
use App\Http\Controllers\DruniCategoriaScrapingController;
use App\Http\Controllers\LookScrapingController;
use App\Http\Controllers\LookfantasticScrapingController;
use App\Http\Controllers\LookfantasticCategoriaScrapingController;


class Kernel extends ConsoleKernel
{
    /**
     * Define the application's command schedule.
     *
     * @param  \Illuminate\Console\Scheduling\Schedule  $schedule
     * @return void
     */
    protected function schedule(Schedule $schedule)
    {
        // $schedule->command('inspire')->hourly();
        $schedule->call(function(){
            echo('Ejecutando ....');
            
            // Tienda Druni
            // Recogemos el controlador para scrapear la tienda y las categorias de ella
            echo "Tienda Druni";

            $tiendaDruni = new DruniScrapingController();

            echo('Ejecutando CostData.');
            // Primero ejecutamos el shippingCostData para scrapear los datos de la tienda (nombre, gastos de envios, gastos minimos)
            $tiendaDruni->shippingCostData($client);
            echo('Terminado CostData.');

            echo('Categorias Druni');
            // Segundo recogemos el controlador para scrapear la tienda y las categorias de ella
            $categoriasDruni = new DruniCategoriaScrapingController();
            echo('Saliendo de categorias Druni');

            // Tercero añadimos a la base de datos las categorias que contiene cada tienda añadiendolo a las tablas(categoriasTienda y subcategoriasTienda)
            echo('Ejecutando Category.');
            $categoriasDruni->category($client);
            echo('Terminado Category.');

            echo('Ejecutando Productos.');
            // Cuarto ejecutamos el productsCategory para scrapear los productos de la tienda generando (precios)
            $tiendaDruni->pageDate($client);
            echo('Terminado Productos.');



            // Tienda Lookfantastic
            // Recogemos el controlador para scrapear la tienda y las categorias de ella

            $tiendaLook = new LookfantasticScrapingController();
            echo "Tienda Look";

            echo('Ejecutando CostData.');
            // Primero ejecutamos el shippingCostData para scrapear los datos de la tienda (nombre, gastos de envios, gastos minimos)
            $tiendaLook->shippingCostData($client);
            echo('Terminado CostData.');

            echo('Categorias Look');
            // Segundo recogemos el controlador para scrapear la tienda y las categorias de ella
            $categoriasLook = new LookfantasticCategoriaScrapingController();
            echo('Saliendo de categorias Look');

            // Tercero añadimos a la base de datos las categorias que contiene cada tienda añadiendolo a las tablas(categoriasTienda y subcategoriasTienda)
            echo('Ejecutando Category.');
            $categoriasLook->category($client);
            echo('Terminado Category.');

            echo('Ejecutando Productos.');
            // Cuarto ejecutamos el productsCategory para scrapear los productos de la tienda generando (precios)
            $tiendaLook->productsCategory($client);
            echo('Terminado Productos.');

            
        })->everyThreeHours();
    }

    /**
     * Register the commands for the application.
     *
     * @return void
     */
    protected function commands()
    {
        $this->load(__DIR__.'/Commands');

        require base_path('routes/console.php');
    }
}
