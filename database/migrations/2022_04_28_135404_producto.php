<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Producto extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Producto', function (Blueprint $table) {
            $table->id();
            $table->string('marca');
            $table->decimal('precio', 5, 2);
            $table->string('nombre');
            $table->string('descripcion');
            $table->integer('valoracion');
            $table->foreignId('id_pagina')
                  ->references('id')->on('Pagina_externa');
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
        Schema::table('Producto', function ($table){
            $table -> dropForeign(['id_pagina']);
        });
        Schema::dropIfExists('Producto');
    }
}
