<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\LoginController;
<<<<<<< HEAD
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Hash;
use Illuminate\Auth\Events\PasswordReset;
=======
>>>>>>> web-screpping

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

<<<<<<< HEAD
Route::get('/', function () {
    return view('home');
});
=======
>>>>>>> web-screpping

Route::get('/marcas', function () {
    return view('marcas');
});
Route::get('/product', function () {
<<<<<<< HEAD
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
/*
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
*/
=======
    return view('layouts/product');
});

// Route::get('/perfil', function () {
//     return view('perfil');
// });

>>>>>>> web-screpping
Route::get('/dashboard', function () {
    return view('dashboard');
})->middleware(['auth'])->name('dashboard');

require __DIR__.'/auth.php';

<<<<<<< HEAD
=======

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
>>>>>>> web-screpping

Route::get('/', [App\Http\Controllers\ProductoController::class, 'top'])->name('top');
Route::get('/marcas', [App\Http\Controllers\ProductoController::class, 'marcas'])->name('marcas');
Route::get('/categorias', [App\Http\Controllers\ProductoController::class, 'categorias'])->name('categorias');
Route::get('/categoria', [App\Http\Controllers\ProductoController::class, 'categorias'])->name('categorias');
Route::get('/perfil', [App\Http\Controllers\UpdateUsersController::class, 'edit'])->name('edit');
Route::get('/update', [App\Http\Controllers\UpdateUsersController::class, 'update'])->name('update');
Route::get('/cambiar', [App\Http\Controllers\CambiarController::class, 'edit'])->name('ea');
Route::put('/cambiar', [App\Http\Controllers\CambiarController::class, 'update'])->name('password_update');
