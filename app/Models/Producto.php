<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        "imagen",
        "nombre",
        "id_marca",
        'id_subcategoria',
        "descripcion",
        'valoracion',
        'id_tienda'
    ];
}
