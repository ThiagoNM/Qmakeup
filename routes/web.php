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

Route::get('/', function () {
    return view('home');
});

Route::get('/marcas', function () {
    return view('marcas');
});
Route::get('/product', function () {
    return view('product');
});
Route::get('/cambiar', function () {
    return view('si');
});
Route::get('/auth/reset-password', function () {
    return view('auth/reset-password');
});

// Route::get('/categorias', function () {
//     return view('categorias');
// });

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
Route::get('/categoria', [App\Http\Controllers\ProductoController::class, 'categorias'])->name('categorias');
Route::get('/perfil', [App\Http\Controllers\UpdateUsersController::class, 'edit'])->name('edit');
Route::put('/{user}/update', [App\Http\Controllers\UpdateUsersController::class, 'update'])->name('update');
