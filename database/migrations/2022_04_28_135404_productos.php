<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Productos extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('productos', function (Blueprint $table) {
            $table->id();
            $table->string('marca');
            $table->decimal('precio', 5, 2);
            $table->string('nombre');
            $table->foreignId('id_categoria')
                ->references('id')->on('categorias');
            $table->string('descripcion');
            $table->integer('valoracion');
            $table->foreignId('id_pagina')
                  ->references('id')->on('paginas_externas');
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
        Schema::table('productos', function ($table){
            $table -> dropForeign(['id_pagina']);
            $table -> dropForeign(['id_categoria']);
        });
        Schema::dropIfExists('productos');
    }
}
