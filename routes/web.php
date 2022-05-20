<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;

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
Auth::routes();

Route::get('/', function () {
    return view('home');
});

Route::get('/marcas', function () {
    return view('marcas');
});
Route::get('/product', function () {
    return view('product');
})->name('producto');



Route::get('/cambiar', function () {
    return view('si');
});
Route::get('/auth/reset-password', function () {
    return view('auth/reset-password');
});

Route::get('/forgot-password', function () {
    return view('auth.forgot-password');

})->middleware('guest')->name('password.request');

Route::post('/forgot-password', function (Request $request) {
    $request->validate(['email' => 'required|email']);
 
    $status = Password::sendResetLink(
        $request->only('email')
    );
 
    return $status === Password::RESET_LINK_SENT
                ? back()->with(['status' => __($status)])
                : back()->withErrors(['email' => __($status)]);
})->middleware('guest')->name('password.email');

Route::get('/reset-password/{token}', function ($token) {
    return view('auth.reset-password', ['token' => $token]);
})->middleware('guest')->name('password.reset');

Route::post('/reset-password', function (Request $request) {
    $request->validate([
        'token' => 'required',
        'email' => 'required|email',
        'password' => 'required|min:8|confirmed',
    ]);
 
    $status = Password::reset(
        $request->only('email', 'password', 'password_confirmation', 'token'),
        function ($user, $password) {
            $user->forceFill([
                'password' => Hash::make($password)
            ])->setRememberToken(Str::random(60));
 
            $user->save();
 
            event(new PasswordReset($user));
        }
    );
 
    return $status === Password::PASSWORD_RESET
                ? redirect()->route('login')->with('status', __($status))
                : back()->withErrors(['email' => [__($status)]]);
})->middleware('guest')->name('password.update');

Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

// Crear Tiendas
Route::get('/scraping/druni/gastos', [App\Http\Controllers\DruniScrapingController::class, 'shippingCostData'])->name('tiendaDruni')->middleware(['auth', 'id_rol:2']);
Route::get('/scraping/look/gastos', [App\Http\Controllers\LookfantasticScrapingController::class, 'shippingCostData'])->name('tiendaLook')->middleware(['auth', 'id_rol:2']);
// Route::get('/scraping/maquillalia/gastos', [App\Http\Controllers\MaquillaliaScrapingController::class, 'shippingCostData']);

// Categorias de cada página
Route::get('/scraping/druni/categorias', [App\Http\Controllers\DruniCategoriaScrapingController::class, 'category'])->name('categoriasDruni')->middleware(['auth', 'id_rol:2']);
Route::get('/scraping/look/categorias', [App\Http\Controllers\LookfantasticCategoriaScrapingController::class, 'category'])->name('categoriasLook')->middleware(['auth', 'id_rol:2']);
// Route::get('/scraping/maquillalia/categorias', [App\Http\Controllers\MaquillaliaCategoriaScrapingController::class, 'category']);

// Web Scraping producto y precios
Route::get('/scraping/druni/productos', [App\Http\Controllers\DruniScrapingController::class, 'pageDate'])->name('productosDruni')->middleware(['auth', 'id_rol:2']);
// Web Scraping precios
Route::get('/scraping/look/productos', [App\Http\Controllers\LookfantasticScrapingController::class, 'productsCategory'])->name('preciosLook')->middleware(['auth', 'id_rol:2']);
// Route::get('/scraping/maquillalia/productos', [App\Http\Controllers\MaquillaliaScrapingController::class, 'productsCategory']);


Route::get('/', [App\Http\Controllers\ProductoController::class, 'top'])->name('top');
Route::get('/marcas', [App\Http\Controllers\ProductoController::class, 'marcas'])->name('marcas');
Route::get('/categorias', [App\Http\Controllers\ProductoController::class, 'categorias'])->name('categorias');
Route::get('/perfil', [App\Http\Controllers\UpdateUsersController::class, 'edit'])->name('perfil');
Route::get('/update', [App\Http\Controllers\UpdateUsersController::class, 'update'])->name('update');
Route::get('/cambiar', [App\Http\Controllers\CambiarController::class, 'edit'])->name('ea');
Route::put('/cambiar', [App\Http\Controllers\CambiarController::class, 'update'])->name('password_update');

Route::get('/prueba', [App\Http\Controllers\PruebaController::class, 'hola'])->middleware(['auth', 'id_rol:2']);
Route::resource('/productoShow', App\Http\Controllers\ProductoController::class);
Route::get('/borrar', [App\Http\Controllers\DruniScrapingController::class, 'EliminarPrecios']);
Route::get('/filtro/{id}', [App\Http\Controllers\ProductoController::class, 'Filtroid'])->name('find');
Route::get('/subcate/{id}', [App\Http\Controllers\ProductoController::class, 'Subcategorias'])->name('subcate');
Route::get('/wishlist/{id}',[App\Http\Controllers\WishlistController::class, 'pedro'])->name('lista');
Route::post('/add-rating',[App\Http\Controllers\RatingController::class, 'add'])->name('add-rating');