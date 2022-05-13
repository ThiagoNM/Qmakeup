<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class CategoriaTienda extends Model
{
    use HasFactory;

    protected $table = 'categorias_tiendas';

    protected $fillable = [
        'nombre',
        'id_categoria',
        'url_categoria',
        'id_tienda'
    ];
}
