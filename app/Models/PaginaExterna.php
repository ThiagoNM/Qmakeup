<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PaginaExterna extends Model
{
    use HasFactory;

    protected $fillable = [
        'url',
        'id_tienda'
    ];

    protected $table = "paginas_externas";
}
