<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubcategoriaTienda extends Model
{
    use HasFactory;
    protected $table = 'subcategorias_tiendas';

    protected $fillable = [
        'nombre',
        'id_subcategoria',
        'url_subcategoria',
        'id_tienda'
    ];
}
