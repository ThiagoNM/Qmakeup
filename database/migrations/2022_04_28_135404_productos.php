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
            $table->string('imagen');
            $table->string('nombre');
            $table->foreignId('id_marca')
                ->references('id')->on('marcas');
            $table->foreignId('id_subcategoria')
                ->references('id')->on('subcategorias_tiendas');
            $table->string('descripcion');
            $table->integer('valoracion');
            $table->foreignId('id_tienda')
                  ->references('id')->on('tiendas');
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
            $table -> dropForeign(['id_tienda']);
            $table -> dropForeign(['id_subcategoria']);
            $table -> dropForeign(['id_marca']);
        });
        Schema::dropIfExists('productos');
    }
}
