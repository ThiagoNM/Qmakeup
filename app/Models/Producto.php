<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Producto extends Model
{
    use HasFactory;

    protected $fillable = [
        'marca',
        'precio',
        'nombre',
        'categoria',
        'descripcion',
        'valoracion',
        'id_pagina'
    ];
}
