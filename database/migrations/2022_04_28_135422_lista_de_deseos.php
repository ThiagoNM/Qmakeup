<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ListaDeDeseos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Lista_de_deseos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto')
                  ->nullable()
                  ->references('id')->on('Producto');
            $table->foreignId('id_usuario')
                  ->nullable()
                  ->references('id')->on('usuarios');
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
        Schema::table('Lista_de_deseos', function ($table){
            $table -> dropForeign(['id_usuario']);
            $table -> dropForeign(['id_producto']);
        });
        Schema::dropIfExists('Lista_de_deseos');

    }
}
