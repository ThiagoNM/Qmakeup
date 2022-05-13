<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UpdateUsers extends Model
{
    use HasFactory;


    protected $fillable = [
        'id',
        'name',
        'email',
        'email_verfied_at',
        'password',
        'rol',
        'remember_token'
    ];
}
