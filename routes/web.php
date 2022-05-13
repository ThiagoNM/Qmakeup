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

// Web Scraping
Route::get('/druni', [App\Http\Controllers\DruniScrapingController::class, 'shippingCostData']);
Route::get('/look', [App\Http\Controllers\LookfantasticScrapingController::class, 'productsCategory']);
Route::get('/maquillalia', [App\Http\Controllers\MaquillaliaScrapingController::class, 'productsCategory']);

// Categorias de cada página
Route::get('/cd', [App\Http\Controllers\DruniCategoriaScrapingController::class, 'category']);
Route::get('/cm', [App\Http\Controllers\MaquillaliaCategoriaScrapingController::class, 'category']);
Route::get('/cl', [App\Http\Controllers\LookfantasticCategoriaScrapingController::class, 'category']);

// Gastos de envio de cada página
Route::get('/gastosd', [App\Http\Controllers\DruniScrapingController::class, 'shippingCostData']);
Route::get('/gastosl', [App\Http\Controllers\LookfantasticScrapingController::class, 'shippingCostData']);
Route::get('/gastosm', [App\Http\Controllers\MaquillaliaScrapingController::class, 'shippingCostData']);

Route::get('/', [App\Http\Controllers\ProductoController::class, 'top'])->name('top');
Route::get('/marcas', [App\Http\Controllers\ProductoController::class, 'marcas'])->name('marcas');
Route::get('/categorias', [App\Http\Controllers\ProductoController::class, 'categorias'])->name('categorias');
Route::get('/categoria', [App\Http\Controllers\ProductoController::class, 'categorias'])->name('categorias');
Route::get('/perfil', [App\Http\Controllers\UpdateUsersController::class, 'edit'])->name('edit');
Route::get('/update', [App\Http\Controllers\UpdateUsersController::class, 'update'])->name('update');
Route::get('/cambiar', [App\Http\Controllers\CambiarController::class, 'edit'])->name('ea');
Route::put('/cambiar', [App\Http\Controllers\CambiarController::class, 'update'])->name('password_update');

Route::get('/prueba', [App\Http\Controllers\PruebaController::class, 'shippingCostData']);
