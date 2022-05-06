<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Usuarios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('usuarios', function (Blueprint $table) {
            $table->id();
            $table->string('username');
            $table->string('correo');
            $table->string('contrasenya');
            $table->foreignId('rol')
                  ->references('id')->on('Rol');
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('usuarios', function ($table){
            $table -> dropForeign(['Rol']);
        });
        Schema::dropIfExists('usuarios');
    }
}
