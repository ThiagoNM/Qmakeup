<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CategoriasTiendas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('categorias_tiendas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('id_categoria')
                    ->references('id')->on('categorias');
            $table->string('url_categoria');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('categorias_tiendas', function ($table){
            $table -> dropForeign(['id_categoria']);
        });
        Schema::dropIfExists('categorias_tiendas');
    }
}
