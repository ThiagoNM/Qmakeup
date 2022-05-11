<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
|
| Here is where you can register web routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| contains the "web" middleware group. Now create something great!
|
*/


Route::get('/marcas', function () {
    return view('layouts/marcas');
});
Route::get('/product', function () {
    return view('layouts/product');
});

// Route::get('/perfil', function () {
//     return view('perfil');
// });

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';


Route::get('/', [App\Http\Controllers\ProductoController::class, 'top'])->name('top');
Route::get('/marcas', [App\Http\Controllers\ProductoController::class, 'marcas'])->name('marcas');
Route::get('/categorias', [App\Http\Controllers\ProductoController::class, 'categorias'])->name('categorias');
#Route::get('/categoria', [App\Http\Controllers\ProductoController::class, 'categoria'])->name('categoria');
Route::get('/categorias/{categoria}/productos', [App\Http\Controllers\ProductoController::class, 'categoria'])->name('categoria');

Route::get('/perfil', [App\Http\Controllers\ProductoController::class, 'perfil'])->name('perfil');
<<<<<<< HEAD
=======
Route::get('/druni', [App\Http\Controllers\DruniScrapingController::class, 'productsCategory'])->name('productsCategory');
Route::get('/look', [App\Http\Controllers\LookfantasticScrapingController::class, 'productsCategory'])->name('productsCategory');
Route::get('/primor', [App\Http\Controllers\PrimorScrapingController::class, 'productsCategory'])->name('productsCategory');
Route::get('/maquillalia', [App\Http\Controllers\MaquillaliaScrapingController::class, 'productsCategory'])->name('productsCategory');

Route::get('/cat', [App\Http\Controllers\CategoriaScrapingController::class, 'category'])->name('category');
Route::get('/category', [App\Http\Controllers\CategoryScrapingController::class, 'category'])->name('category');
Route::get('/cd', [App\Http\Controllers\DruniCategoriaScrapingController::class, 'category'])->name('category');
Route::get('/cm', [App\Http\Controllers\MaquillaliaCategoriaScrapingController::class, 'category'])->name('category');
Route::get('/cl', [App\Http\Controllers\LookfantasticCategoriaScrapingController::class, 'category'])->name('category');


>>>>>>> b6b9043cd608abe61ad1144d046b1ac4966c4bf5

// Web Scraping
Route::get('/druni', [App\Http\Controllers\DruniScrapingController::class, 'productsCategory']);
Route::get('/look', [App\Http\Controllers\LookfantasticScrapingController::class, 'productsCategory']);
Route::get('/primor', [App\Http\Controllers\PrimorScrapingController::class, 'productsCategory']);
Route::get('/maquillalia', [App\Http\Controllers\MaquillaliaScrapingController::class, 'productsCategory']);

// Categorias de cada página
Route::get('/cd', [App\Http\Controllers\DruniCategoriaScrapingController::class, 'category']);
Route::get('/cm', [App\Http\Controllers\MaquillaliaCategoriaScrapingController::class, 'category']);
Route::get('/cl', [App\Http\Controllers\LookfantasticCategoriaScrapingController::class, 'category']);


// Gastos de envio de cada página
Route::get('/gastosd', [App\Http\Controllers\DruniScrapingController::class, 'shippingCostData']);
Route::get('/gastosl', [App\Http\Controllers\LookfantasticScrapingController::class, 'shippingCostData']);
Route::get('/gastosm', [App\Http\Controllers\MaquillaliaScrapingController::class, 'shippingCostData']);

