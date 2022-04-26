<?php

use Illuminate\Support\Facades\Route;

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
    return view('layouts/home');
});
Route::get('/login', function () {
    return view('layouts/login');
});
Route::get('/marcas', function () {
    return view('layouts/marcas');
});
Route::get('/producto', function () {
    return view('layouts/producto');
});
Route::get('/registro', function () {
    return view('layouts/registro');
});
Route::get('/categorias', function () {
    return view('layouts/categorias');
});


