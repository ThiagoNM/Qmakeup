<?php

namespace App\Http\Controllers;

use App\Http\Controllers\Controller;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

use Illuminate\Http\Request;

class LoginController extends Controller
{
    use AuthenticatedUsers;

    protected $redirectTo = RouteServiceProvider::Home;

    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }
}
