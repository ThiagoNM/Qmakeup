<?php

namespace App\Http\Controllers;
use App\Models\Producto;
use App\Models\Lista_de_deseo;
use Illuminate\Http\Request;
use App\Http\Controllers\Redirect;

class WishlistController extends Controller
{
    public function pedro($producto)
    {
        $esta = Lista_de_deseo::all()->where('id_usuario','=', \Auth::user()->id);
       
        $existe = false;
        foreach($esta as $encontrado){
            
            if($encontrado->id_producto == $producto){
               
                $encontrado->delete();
                $existe = true;
            } else{

            }
            
        }
      
        if($existe == false){
            $ok = Lista_de_deseo::create([
                'id_producto' => $producto,
                'id_usuario' => \Auth::user()->id,
                'estado' => 1,
            ]);
        }
      
        if($existe){
            return back()->with('success','Producto eliminado de la lista de deseos correctamente');
        } else {
            return back()->with('success','Producto anadido a la lista de deseos correctamente');
        }
    }
}
