<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lista_de_deseo extends Model
{
    use HasFactory;
    protected $table = 'listas_de_deseos';
    protected $fillable = [
        "id_producto",
        "id_usuario",
        "estado"
    ];
}
