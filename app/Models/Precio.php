<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Precio extends Model
{
    use HasFactory;

    protected $fillable = [
        "id_producto",
        'id_tienda',
        "precio",
        "url_producto"
    ];
}
