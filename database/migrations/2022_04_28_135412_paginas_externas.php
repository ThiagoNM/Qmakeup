<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaginasExternas extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('paginas_externas', function (Blueprint $table) {
            $table->id();
            $table->string('url');
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
        Schema::table('paginas_externas', function ($table){
            $table -> dropForeign(['id_tienda']);
        });
        Schema::dropIfExists('paginas_externas');
    }
}
