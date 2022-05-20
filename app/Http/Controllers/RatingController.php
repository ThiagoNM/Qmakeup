<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Rating;
use App\Models\Producto;
use Illuminate\Support\Facades\Auth;


class RatingController extends Controller
{
    public function add(Request $request){
        $stars_rated = $request->input('product_rating');
        $id_producto = $request->input('id_producto');
        
        $producto_check = Producto::all()->where('id',$id_producto);
        if($producto_check)
        {
            $existe_valo = Rating::where('id_usuario', \Auth::user()->id)->where('id_producto', $id_producto)->first(); 
            if($existe_valo)
            {
                
                $existe_valo->stars_rated = $stars_rated;
                $existe_valo->update([
                    'stars_rated'=>$stars_rated
                ]);
            } else {
                Rating::create([
                'id_usuario' => \Auth::user()->id,
                'id_producto' => $id_producto,
                'stars_rated' => $stars_rated
                ]); 
            }
            
            return redirect()->back()->with('status', 'Gracias por valorar nuestro producto');
        }else{
            return redirect()->back()->with('status', 'Error');

        }
    }
}
