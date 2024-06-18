<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateRajaongkirCitiesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('rajaongkir_cities', function (Blueprint $table) {
            $table->id();
            $table->integer('city_id')->unique();
            $table->string('city_name');
            $table->string('type');
            $table->integer('province_id');
            $table->string('postal_code');
            $table->foreign('province_id')->references('province_id')->on('rajaongkir_provinces')->onDelete('cascade');
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
        Schema::dropIfExists('rajaongkir_cities');
    }
}
