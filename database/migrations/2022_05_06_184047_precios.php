<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Precios extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('precios', function (Blueprint $table) {
            $table->id();
            $table->foreignId('id_producto')
                  ->references('id')->on('productos');
            $table->foreignId('id_tienda')
                    ->references('id')->on('tiendas');
            $table->decimal('precio',8,2);
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
        Schema::table('precios', function ($table){
            $table -> dropForeign(['id_tienda']);
            $table -> dropForeign(['id_producto']);
        });
        Schema::dropIfExists('precios');
    }
}
