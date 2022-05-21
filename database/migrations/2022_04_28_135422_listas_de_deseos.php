<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ListasDeDeseos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('listas_de_deseos', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto')
                  ->nullable()
                  ->references('id')->on('productos');
            $table->foreignId('id_usuario')
                  ->nullable()
                  ->references('id')->on('users');
            $table->boolean('estado');     
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
        Schema::table('listas_de_deseos', function ($table){
            $table -> dropForeign(['id_usuario']);
            $table -> dropForeign(['id_producto']);
        });
        Schema::dropIfExists('listas_de_deseos');

    }
}
