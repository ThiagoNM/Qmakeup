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
        Schema::table('categorias_tiendas', function ($table){
            $table -> dropForeign(['id_categoria']);
            $table -> dropForeign(['id_tienda']);

        });
        Schema::dropIfExists('categorias_tiendas');
    }
}
