<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class PaginaExterna extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Pagina_externa', function (Blueprint $table) {
            $table->id();
            $table->string('url');
            $table->foreignId('id_tienda')
                  ->references('id')->on('Tienda');
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
        Schema::table('Pagina_externa', function ($table){
            $table -> dropForeign(['id_tienda']);
        });
        Schema::dropIfExists('Pagina_externa');
    }
}
