<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\Rating;
use App\Models\Producto;
use App\Models\Lista_de_deseo;
use App\Http\Controllers\Auth;
use Illuminate\Http\Request;

class UpdateUsersController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function edit()
    {
        $producto = [];
        $ok = Lista_de_deseo::all()->where('id_usuario' ,'=',\Auth::user()->id);
        foreach ($ok as $elemento) {
            array_push($producto, Producto::all()->where('id', '=',$elemento->id_producto)->first());
        }
        $productos = Producto::all()->where("id")->first();
        $ratings = Rating::all();
        return view('perfil',[
            'user' => \Auth::user(),
            'productos' => $producto
        ],compact('ratings','productos'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {
        $request->validate([
            'name' => 'required',
        ]);
           
        $user=\Auth::user();

        $ok =  $user->updateOrFail ([
            'name' => $request->name
        ]);
        
        return redirect()->route('top')->with('succes', 'Actualizado correctamente');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        //
    }
}
