<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class SubcategoriasTienda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('subcategorias_tiendas', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->foreignId('id_subcategoria')
                    ->references('id')->on('subcategorias');
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
        Schema::table('subcategorias_tiendas', function ($table){
            $table -> dropForeign(['id_subcategoria']);
        });
        Schema::dropIfExists('subcategorias_tiendas');
    }
}
