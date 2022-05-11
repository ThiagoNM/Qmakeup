<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class Tienda extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('Tienda', function (Blueprint $table) {
            $table->id();
            $table->string('nombre');
            $table->decimal('gastos', 4,2);
            $table->decimal('gastos_min', 4,2);
            $table->integer('impuestos');
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
        Schema::dropIfExists('Tienda');
    }
}
